<?php
  
  namespace M\Orm\Builder;

  class From extends \M\Orm\Builder {
    
    public static function parse(\M\Orm $orm, array $args) {
      return 'FROM `'. $args[0] .'`';
    }
  }