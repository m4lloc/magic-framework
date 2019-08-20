<?php
  
  namespace M\Orm\Builder;

  class Into extends \M\Orm\Builder {
    
    public static function parse(\M\Orm $orm, array $args) {
      return 'INTO `'. $args[0] .'`';
    }
  }