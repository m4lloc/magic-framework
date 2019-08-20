<?php
  
  namespace M\Orm\Builder;

  class Insert extends \M\Orm\Builder {
    
    public static function parse(\M\Orm $orm, array $args) {
      return 'INSERT';
    }
  }