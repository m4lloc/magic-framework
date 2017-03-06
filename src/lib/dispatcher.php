<?php

  namespace Magic;

  class Dispatcher {

    private $route = false;

    public function load() {
      $this->determine_route();
      $this->invoke_controller();
    }

    private function determine_route() {
      $dispatcher = \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) {
        foreach(\Magic\Router::$routes as $route) {
          $r->addRoute($route['method'], $route['route_pattern'], $route['handler']);
        }
      });

      $uri = $_SERVER['REQUEST_URI'];

      // Strip query string (?foo=bar) and decode URI
      if (false !== $pos = strpos($uri, '?')) {
          $uri = substr($uri, 0, $pos);
      }
      $uri = rawurldecode($uri);

      $this->route = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $uri);
    }

    private function invoke_controller() {
      var_dump($this->route);

      switch($this->route[0]) {
        case \FastRoute\Dispatcher::NOT_FOUND:
          echo 'NOT_FOUND'. PHP_EOL;
          $controller = new \Magic\Controller\PageNotFound();
          $controller->index();
          break;
        case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
          echo 'METHOD_NOT_ALLOWED'. PHP_EOL;
          $allowedMethods = $routeInfo[1];
          $controller = new \Magic\Controller\MethodNotAllowed();
          $controller->index();
          break;
        case \FastRoute\Dispatcher::FOUND:
          echo 'FOUND'. PHP_EOL;
          $handler = $routeInfo[1];
          $vars = $routeInfo[2];
          // ... call $handler with $vars
          break;
      }
    }
  }
