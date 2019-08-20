<?php
  
  namespace M\Orm\Builder;

  class Limit extends \M\Orm\Builder {
    
    public static function parse(\M\Orm $orm, array $args) {
      return 'LIMIT '. $args[0];
    }
  }