<?php

  namespace Magic\Controller;

  class InternalServerError extends \Magic\Controller {
    public function index() {
      echo '<h1>500 - Internal server error</h1>';
    }
  }
