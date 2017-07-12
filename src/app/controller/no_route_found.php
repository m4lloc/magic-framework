<?php

  namespace \Magic\Controller;

  class NoRouteFound extends Controller {

      public function index() {
        echo '<h1>404 - Route definition not found</h1>';
      }
  }
