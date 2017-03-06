<?php

  // use function left_join as leftJoin;
  // use function left_inner_join as leftInnerJoin;
  // use function left_outer_join as leftOuterJoin;
  // use function right_join as rightJoin;
  // use function right_inner_join as rightInnerJoin;
  // use function right_outer_join as rightOuterJoin;
  // use function find_one as findOne;
  // use function find_one_by as findOneBy;
  // use function has_one as hasOne;
  // use function hasMany as hasMany;
  // use function execute as all;

  namespace Magic;

  /*abstract*/ class Model {
    private $attributes = [];
    private $query = [];
    private $action = [];

    public function select($columns='*') {
      $this->query[] = [__FUNCTION__, $this->parse_select($columns)];
      $this->register_action(__FUNCTION__);
      return $this;
    }

    public function from($tables) {
      $this->query[] = [__FUNCTION__, $this->parse_tables($tables)];
      $this->register_action(__FUNCTION__);
      return $this;
    }

    public function join($tables) {
      $this->query[] = [__FUNCTION__, $this->parse_tables($tables)];
      return $this;
    }

    public function left_join($tables) {
      $this->query[] = [__FUNCTION__, $this->parse_tables($tables)];
      return $this;
    }

    public function left_inner_join($tables) {
      $this->query[] = [__FUNCTION__, $this->parse_tables($tables)];
      return $this;
    }

    public function left_outer_join($tables) {
      $this->query[] = [__FUNCTION__, $this->parse_tables($tables)];
      return $this;
    }

    public function right_join($tables) {
      $this->query[] = [__FUNCTION__, $this->parse_tables($tables)];
      return $this;
    }

    public function right_inner_join($tables) {
      $this->query[] = [__FUNCTION__, $this->parse_tables($tables)];
      return $this;
    }

    public function right_outer_join($tables) {
      $this->query[] = [__FUNCTION__, $this->parse_tables($tables)];
      return $this;
    }

    public function on() {
      return $this;
    }

    public function where($statement) {
      if(!empty($statement)) {
        $this->query[] = [__FUNCTION__, $this->parse_where($statement)];
        $this->register_action(__FUNCTION__);
      }
      return $this;
    }

    public function order() {
      return $this;
    }

    public function group() {
      return $this;
    }

    public function having() {
      return $this;
    }

    public function limit($limit) {
      $this->query[] = [__FUNCTION__, $limit];
      return $this;
    }

    public function offset($offset) {
      $this->query[] = [__FUNCTION__, $offset];
      return $this;
    }

    public function find($id=false) {
      if($id !== false) {
        $this->id = $id;
      }
      return $this->execute();
    }

    public function find_one($id) {
      $this->limit(1);
      return $this->find($id);
    }

    public function find_by($column, $value) {
      $this->{$column} = $value;
      return $this->execute();
    }

    public function find_one_by($column, $value) {
      $this->limit(1);
      return $this->find_by($column, $value);
    }

    public function first() {
      $this->limit(1);
      return $this->execute();
    }

    public function all() {
      return $this->execute();
    }

    public function delete() {
      array_unshift($this->query, [__FUNCTION__, '']);
      return $this->execute();
    }

    public function save() {
      $function = (isset($this->attributes['id']) && !empty($this->attributes['id'])) ? 'update' : 'insert' ;
      array_unshift($this->query, [$function, '']);
      $this->register_action($function);
      return $this->execute();
    }

    public function truncate() {
      $this->query[] = [__FUNCTION__, ''];
      return $this->execute();
    }

    public function sql() {
      $sql = '';
      foreach($this->query as $index => $query) {
        list($function, $statement) = $query;
        switch($function) {
          case 'insert':
            $sql .= 'INSERT INTO '. $this->table_name() .' (`'. implode('`, `', array_keys($this->attributes)) .'`) VALUES('. implode(', ', array_fill(0, sizeof($this->attributes), '?')) .')';
            break;
          case 'update':
            // Shift ID attribute to the back
            $id = $this->attributes['id']; unset($this->attributes['id']); $this->attributes['id'] = $id;
            // Parse for set statement
            $attributes = array_filter(array_map(function($column, $value) {
              if($column != 'id') {
                return '`'. $column .'`=?';
              }
            }, array_keys($this->attributes), $this->attributes));
            // Build SQL
            $sql .= 'UPDATE '. $this->table_name() .' SET '. implode(', ', $attributes) .' WHERE id=? LIMIT 1';
            break;
          case 'select':
            $sql .= 'SELECT '. $statement;
            break;
          case 'from':
            $sql .= ' FROM '. $statement;
            break;
          case 'join':
          case 'left_join':
          case 'left_inner_join':
          case 'left_outer_join':
          case 'right_join':
          case 'right_inner_join':
          case 'right_outer_join':
            $sql .= ' '. strtoupper(str_replace('_',' ', $function)) .' '. $statement;
            break;
          case 'on':
            $sql .= ' ON '. $statement;
            break;
          case 'where':
            $sql .= ' WHERE '. $statement;
            break;
          case 'order':
            $sql .= ' ORDER BY '. $statement;
            break;
          case 'group':
            $sql .= ' GROUP BY '. $statement;
            break;
          case 'having':
            $sql .= ' HAVING '. $statement;
            break;
          case 'limit':
            $sql .= ' LIMIT '. $statement;
            break;
          case 'offset':
            $sql .= ' OFFSET '. $statement;
            break;
          case 'truncate':
            $sql = 'TRUNCATE '. $this->table_name();
            break;
        }
        unset($this->query[$index]);
      }
      return $sql;
    }

    public function has_one($model) {}

    public function has_many($model) {}

    private function execute() {
      if(!isset($this->action['select']) && !isset($this->action['update']) && !isset($this->action['insert'])) {
        $this->select('fuck');
        if(!isset($this->action['from'])) { $this->from([$this->table_name()]); }
        if(!isset($this->action['where'])) { $this->where($this->where_attributes()); }
      }

      // try {
			// 	$result = [];
			// 	if($prepare = $this->prepare()) {
      //     if(sizeof($this->attributes) > 0) {
      //       $aParams = array( );
			// 			foreach( $mVars as $iKey => $sVar ) {
			// 				$aParams[0] = $aParams[0] . self::getType( $sVar );
			// 				$aParams[] = &$mVars[$iKey];
			// 			}
			// 			call_user_func_array( array( $oPrepare, 'bind_param' ), $aParams );
      //     }
      //
			// 		$prepare->execute();
			// 		$error = mysqli_error( self::$_conn[self::$_currentConn] );
			// 		if( ! empty( $error ) ) {
			// 			trigger_error( 'Failed to execute prepared query '. $sSql .'<br>['. self::$_conn[self::$_currentConn]->errno .'] '. self::$_conn[self::$_currentConn]->error, E_USER_WARNING );
			// 		}
      //
			// 		switch( strtoupper( current( explode( ' ', $sSql ) ) ) ) {
			// 			case 'SHOW':
			// 			case 'SELECT':
			// 					$aKeys = array( );
			// 					$aRow = array( );
			// 					$aMetaKeys = $oPrepare->result_metadata( )
			// 						->fetch_fields( );
			// 					foreach( $aMetaKeys as $aMetaKey ) {
			// 						$aKeys[] = &$aRow[$aMetaKey->name];
			// 					}
			// 					call_user_func_array( array( $oPrepare, 'bind_result' ), $aKeys );
			// 					$aFields = array_keys( $aRow );
			// 					while( $oPrepare->fetch( ) ) {
			// 						foreach( $aFields as $sField ) {
			// 							$aRowResult[$sField] = $aRow[$sField];
			// 						}
			// 						$aResult[] = $aRowResult;
			// 					}
			// 					return $aResult;
			// 				break;
			// 			case 'INSERT':
			// 					return $prepare->insert_id;
			// 				break;
			// 			case 'UPDATE':
			// 					return $prepare->affected_rows;
			// 			case 'DELETE':
			// 					return true;
			// 				break;
			// 		}
			// 	}
			// 	return false;
			// } catch( Exception $oException ) {
			// 	trigger_error( 'Failed to execute prepared query '. $sSql .'<br>['. self::$_conn[self::$_currentConn]->errno .'] '. self::$_conn[self::$_currentConn]->error, E_USER_WARNING );
			// }

      return $this->sql();
    }

		private static function getType( $mVar ) {
			switch( gettype( $mVar ) ) {
				case 'boolean':
				case 'integer':
					return 'i';
				case 'double':
					return 'd';
				case 'string':
				case 'array':
				case 'object':
				case 'resource':
				default:
					return 's';
			}
		}

		private function prepare() {
      $db = \Magic\Core\Model\Connect::get_instance();
      return $db->prepare($this->sql);
		}

    private static function parse_select($columns) {
      return $columns;
      $sql = '';
      switch(gettype($columns)) {
        case 'array':
          foreach($columns as $alias => $column) {
            if(strpos($column, '.')) {
              $column = implode('`.`', explode('.', $column));
            }
            $sql .= " `$column`";
            $sql .= " `$column`";
            if(!is_numeric($alias)) {
              $sql .= " AS `$alias`";
            }
          }
          return trim($sql);

        case 'string':
          return trim($columns);
      }
      return false;
    }

    private static function parse_tables($tables) {
      switch(gettype($tables)) {
        case 'array':
          $sql = '';
          foreach($tables as $alias => $table) {
            $sql .= " `$table`";
            if(!is_numeric($alias)) {
              $sql .= " AS `$alias`";
            }
          }
          return trim($sql);
        case 'string':
          return trim($tables);
      }
      return false;
    }

    private static function parse_where($statement) {
      switch(gettype($statement)) {
        case 'array':
          $sql = '';
          foreach($statement as $column => $value) {
            $sql .= '`'. $column .'` '. ((strpos($value,'%')) ? 'LIKE' : '=') .' ?';
          }
          return trim($sql);
        case 'string':
          return trim($statement);
      }
      return false;
    }

    private function where_attributes() {
      $attributes = array_filter(array_map(function($column, $value) {
        return '`'. $column .'` '. ((strpos($value,'%')) ? 'LIKE' : '=') .' ?';
      }, array_keys($this->attributes), $this->attributes));
      return implode(' AND ', $attributes);
    }

    public function __call($name, $arguments) {
      if(strpos($name, 'find_by') || strpos($name, 'findBy')) {
        $column = strtolower(preg_replace("/^find_by|^findBy/", '', $name));
        return $this->find_by($column, $arguments);
      }

      if(strpos($name, 'find_one_by') || strpos($name, 'findOneBy')) {
        $column = strtolower(preg_replace("/^find_one_by|^findOneBy/", '', $name));
        return $this->find_one_by($column, $arguments);
      }
    }

    public function __set($name, $value) {
      return $this->attributes[$name] = $value;
    }

    public function &__get($name) {
      return $this->attributes[$name];
    }

    private function table_name() {
      $names = explode('\\', get_class($this));
      return strtolower(preg_replace('/([^\s])([A-Z])/', '\1_\2', end($names)));
    }

    private function register_action($action) {
      if(!isset($this->action[$action])) {
        $this->action[$action] = 0;
      }
      $this->action[$action]++;
    }
  }
