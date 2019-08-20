<?php
  
  namespace M\Orm\Builder;

  class Select extends \M\Orm\Builder {
    
    public static function parse(\M\Orm $orm, array $args) {
      if(empty($args[0])) {
        $args[0] = '*';
      }
      return 'SELECT '. $args[0];
    }
  }