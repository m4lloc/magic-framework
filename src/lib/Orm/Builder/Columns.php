<?php
  
  namespace M\Orm\Builder;

  class Columns extends \M\Orm\Builder {
    
    public static function parse(\M\Orm $orm, array $args) {
      $args = (sizeof($args) == 1) ? $args[0] : $args ;

      $columns = [];
      foreach($args as $column){
        if(isset($column[3]) && $column[3] == 'primary') {
          $columns[] = '`'. $column[0] .'` '. $column[1] .'('. $column[2] .') NOT NULL AUTO_INCREMENT';
          $columns[] = 'PRIMARY KEY ('. $column[0] .')';
          continue;
        }
        $columns[] = '`'. $column[0] .'` '. $column[1] .'('. $column[2] .')';
      }
      return '('. implode(', ', $columns) .')';
    }
  }