<?php

  class error extends \Magic\Initializer {

    public function init() {
      if(getenv('MAGIC_SENTRY_ENABLED') == 'true') {
        $client = new Raven_Client(getenv('SENTRY_DSN'));
        
        $handler = new Raven_ErrorHandler($client);
        $handler->registerExceptionHandler();
        $handler->registerErrorHandler();
        $handler->registerShutdownFunction();
      }
    }
  }
