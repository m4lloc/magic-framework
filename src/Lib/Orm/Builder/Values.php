<?php
  
  namespace M\Orm\Builder;

  class Values extends \M\Orm\Builder {
    
    public static function parse(\M\Orm $orm, array $args) {
      $columns = array_keys($args[0]);
      return '('. implode(', ', $columns) .') VALUES(:'. implode(', :', $columns) .')';
    }
  }