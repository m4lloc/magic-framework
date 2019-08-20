<?php
  
  namespace M\Orm\Builder;

  class Create extends \M\Orm\Builder {
    
    public static function parse(\M\Orm $orm, array $args) {
      return 'CREATE';
    }
  }