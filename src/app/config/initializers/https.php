<?php

  class https extends \M\Initializer {

    public static $before = 'error';

    public function init() {
      if(!isset($_SERVER['HTTPS']) && getenv('APP_ENV') == 'production') {
        header('Location: https://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], true, 301);
        exit;
      }
    }
  }
