<?php
  
  namespace M\Orm\Builder;

  class Table extends \M\Orm\Builder {
    
    public static function parse(\M\Orm $orm, array $args) {
      return 'TABLE `'. $args[0] .'`';
    }
  }