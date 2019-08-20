<?php

  namespace M\Initializer;

  class Https extends \M\Initializer {

    public static $before = 'error';

    public function init() {
      if($this->shouldRedirect()) {
        $this->setHeaderLocation();
        $this->terminate();
      }
    }

    private function shouldRedirect() : bool {
      $redirect_envs = ['production', 'test'];
      if(!isset($_SERVER['HTTPS']) && in_array(getenv('APP_ENV'), $redirect_envs)) {
        return true;
      }
      return false;
    }

    private function setHeaderLocation() : void {
      header('Location: https://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], true, 301);
    }

    private function terminate() : void {
      if(getenv('APP_ENV') != 'test') {
        exit;
      }
    }
  }
