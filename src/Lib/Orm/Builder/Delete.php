<?php
  
  namespace M\Orm\Builder;

  class Delete extends \M\Orm\Builder {
    
    public static function parse(\M\Orm $orm, array $args) {
      return 'DELETE';
    }
  }