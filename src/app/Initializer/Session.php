<?php

  namespace M\Initializer;

  class Session extends \M\Initializer {

    public function init() {
      session_start();
      // @TODO set session timeout and lifetime
    }
  }
