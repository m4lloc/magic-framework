<?php
  
  namespace M\Orm\Builder;

  class Set extends \M\Orm\Builder {
    
    public static function parse(\M\Orm $orm, array $args) {
      $args = (sizeof($args) == 1) ? $args[0] : $args ;
      $columns = [];
      foreach($args as $column => $value){
        $columns[] = '`'. $column .'` = :'. $column;
      }
      return 'SET '. implode(', ', $columns);
    }
  }