<?php
  
  namespace M\Orm\Builder;

  class Database extends \M\Orm\Builder {
    
    public static function parse(\M\Orm $orm, array $args) {
      return 'DATABASE '. $args[0];
    }
  }