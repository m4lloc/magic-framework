<?php
  
  namespace M\Orm\Builder;

  class Orwhere extends \M\Orm\Builder {
    protected static $delimiter = ' OR ';
  }