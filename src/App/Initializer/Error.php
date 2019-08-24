<?php

  namespace M\Initializer;

  class Error extends \M\Initializer {

    public function init() {
      $dsn = getenv('SENTRY_DSN', '');

      if(!empty($dsn)) {
        \Sentry\init([
          'dsn' => $dsn,
          'environment' => getenv('APP_ENV'),
        ]);
      }
    }
  }
