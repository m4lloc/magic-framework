<?php
  
  namespace M\Orm\Builder;

  class Where extends \M\Orm\Builder {
    protected static $delimiter = ' AND ';

    public static function parse(\M\Orm $orm, array $args) {
      $where = [];
      switch(gettype($args[0])) {
        case 'array':
          foreach($args[0] as $name => $value) {
            $orm->{$name} = $value;
            $where[] = '`'. $name .'` = :'. $name;
          }
          break;
        case 'string':
          $where[] = $args[0];
          break;
      }
      return 'WHERE '. implode(self::$delimiter, $where);
    }
  }