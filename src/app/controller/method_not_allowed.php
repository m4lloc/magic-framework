<?php

  namespace Magic\Controller;

  class MethodNotAllowed extends \Magic\Controller {
    public function index() {
      echo '<h1>Method not allowed</h1>';
    }
  }
