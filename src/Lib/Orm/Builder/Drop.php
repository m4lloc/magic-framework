<?php
  
  namespace M\Orm\Builder;

  class Drop extends \M\Orm\Builder {
    
    public static function parse(\M\Orm $orm, array $args) {
      return 'DROP';
    }
  }