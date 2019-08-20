<?php

  namespace M;

  // ORM Exceptions
  class OrmException extends \Exception { }
  // class OrmBuildException extends \OrmException { }
  class OrmConnectionException extends OrmException { }
  class OrmPrepareException extends OrmException { }
  class OrmExecutionException extends OrmException { }

  // ORM Builder Exceptions
  class OrmBuilderException extends OrmException { }
  class OrmBuilderComponentNotImplementedException extends OrmException { }
  class OrmBuilderParserNotImplementedException extends OrmException { }

	class Orm {

    private $components                = [];
    private $values                    = [];

    private static $c                  = false;
    private static $prepared           = [];

    protected static $primary_key      = 'id';

    public function __construct(array $config=[]) {
      if(sizeof($config) == 0 && self::$c !== false) {
        return;
      }

      $config = $config + [
        'hostname' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'database' => null
      ];

      try {
        self::$c = new \PDO(
          'mysql:host='. $config['hostname'] .';dbname='. $config['database'],
          $config['username'],
          $config['password'],
          [
            \PDO::MYSQL_ATTR_DIRECT_QUERY => false,   // Perform direct queries, don't use prepared statements.
            \PDO::MYSQL_ATTR_FOUND_ROWS => true       // Return the number of found (matched) rows, not the number of changed rows.
          ]
        );
      } catch (\PDOException $e) {
        throw new OrmConnectionException($e->getMessage());
      }
    }

		public function __set(string $name, $value=null) {
      $this->values[$name] = $value;
    }

		public function &__get(string $name) {
			return $this->values[$name];
    }

    public function __call(string $name, array $args) {
      $this->components[$name] = $args;
      return $this;
    }

    public function build() {
      return (new \M\Orm\Builder($this, $this->components))
        ->sql();
    }

		public static function query(string $sql, array $variables=[]) {
      return (new self())
        ->exec($sql, $variables);
		}
    
    private function variables(){
      return $this->values;
    }

    private function prepare(string $sql=null) {
      if(empty($sql)) {
        $sql = $this->build();
      }
      try {
        $s = self::$c->prepare($sql);
        if($s === false) {
          throw new OrmPrepareException(
            '['. self::$c->errorInfo()[0] .' '. self::$c->errorInfo()[1] .'] '. self::$c->errorInfo()[2] .': "'. $sql .'"');
        }
        return $s;
      } catch (\PDOException $e) {
        throw new OrmPrepareException($e->getMessage());
      }
    }

		public function exec(string $sql=null, array $variables=null) {
      if(($s = $this->prepare($sql)) !== false) {
        if(empty($variables)) {
          $variables = ($this->variables()) ? $this->variables() : null ;
        }
        if(!$s->execute($variables)) {
          throw new OrmExecutionException(
            '['. $s->errorInfo()[0] .' '. $s->errorInfo()[1] .'] '. $s->errorInfo()[2] .': "'. $s->queryString .'" '. print_r($variables, true));
        }
        return $this->execResult($s);
      }
    }

    private function execIsFetch(string $sql) {
      list($type) = explode(' ', $sql);
      switch(strtolower($type)) {
        case 'select':
          return true;
      }
      return false;
    }

    private function execIsUpdate(string $sql) {
      list($type) = explode(' ', $sql);
      switch(strtolower($type)) {
        case 'update':
        case 'delete':
          return true;
      }
      return false;
    }

    private function execResult(\PDOStatement $s) {
      if($this->execIsFetch($s->queryString)) {
        return $s->fetchAll(\PDO::FETCH_CLASS, $this->execModel($s->queryString));
      }
      if($this->execIsUpdate($s->queryString)) {
        return $s->rowCount();
      }
      return true;
    }

    private function execModel(string $sql) {
      list(, $from) = explode('from ', strtolower($sql), 2);
      list($table) = explode(' ', trim($from), 2);
      return ucfirst(trim($table, '`'));
    }

		public function find() {
      $this->select();
			return $this->exec();
    }
    
    public function findOne() {
      $this->limit(1);
			return current($this->find());
		}

		public function save() {
      if($this->{self::$primary_key} > 0) {
        $update = $this->execUpdate();
      }
      if(!isset($update) || $update == 0) {
        return $this->execInsert();
      }
      return $update;
		}

    private function execUpdate() {
      unset($this->components['insert']);
      unset($this->components['into']);

      $variables = $this->variables();
      unset($variables[self::$primary_key]);

      $this->update(strtolower(get_called_class()));
      $this->set($variables);
      $this->where([
        self::$primary_key => $this->{self::$primary_key}
      ]);
      return $this->exec();
    }

    private function execInsert() {
      $this->components = [];

      $this->insert();
      $this->into(strtolower(get_called_class()));
      $this->values($this->variables());
      $this->exec();
    }

		public function remove() {
      unset($this->components['insert']);
      unset($this->components['into']);
      
      $this->delete();
      return $this->exec();
    }
    
    public function getComponents() {
      return $this->components;
    }
  }
