<?php

  namespace M\Initializer;

  class Database extends \M\Initializer {

    public function init() {
      if(!empty(getenv('MYSQL_DSN'))) {
        $c = [
          'dsn' => getenv('MYSQL_DSN')
        ];
      } else {
        $c = [
          'user' => getenv('MYSQL_USER'),
          'pass' => getenv('MYSQL_PASS'),
          'host' => getenv('MYSQL_HOST'),
          'database' => getenv('MYSQL_DATABASE')
        ];
      }

      new \M\Orm($c);
    }
  }
