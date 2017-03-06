<?php

  class session extends \Magic\Initializer {

    public function init() {
      session_start();
      
      if(!isset($_SESSION['account'])){
        $_SESSION['account'] = array( );
        $_SESSION['account']['login'] = false;
        $_SESSION['account']['info'] = array(
          'name' => 'Guest'
        );
      }
    }
  }
