<?php
  
  namespace M\Orm\Builder;

  class Update extends \M\Orm\Builder {
    
    public static function parse(\M\Orm $orm, array $args) {
      return 'UPDATE `'. $args[0] .'`';
    }
  }