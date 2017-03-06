<?php

  class https extends \Magic\Initializer {

    public static $before = 'error';

    public function init() {
      if(!isset($_SERVER['HTTPS']) && getenv('APP_ENVIRONMENT') == 'production') {
        header('Location: https://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], true, 301);
        exit;
      }
    }
  }
