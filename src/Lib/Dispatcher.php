<?php

  namespace M;

  class Dispatcher {

    private $route;
    private $class;
    private $method;
    private $params = [];

    public function load() {
      $this->determine_route();
      $this->determine_controller();
      $this->invoke();
      $this->render();
    }

    private function determine_route() {
      $dispatcher = \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) {
        foreach(\M\Router::$routes as $route) {
          $r->addRoute($route['method'], $route['route_pattern'], $route['handler']);
        }
      });

      // Strip query string (?foo=bar) and decode URI
      $uri = $_SERVER['REQUEST_URI'];
      if(false !== $pos = strpos($uri, '?')) {
          $uri = substr($uri, 0, $pos);
      }
      $uri = rawurldecode($uri);

      $this->route = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $uri);
    }

    private function determine_controller() {
      switch($this->route[0]) {
        case \FastRoute\Dispatcher::NOT_FOUND:
          $this->class = '\\M\\Controller\\PageNotFound';
          break;
        case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
          $this->class = '\\M\\Controller\\MethodNotAllowed';
          $params = ['allowed_methods' => $this->route[1]];
          echo 'METHOD_NOT_ALLOWED'. PHP_EOL;
          $c = new \M\Controller\MethodNotAllowed();
          $c->index();
          break;
        case \FastRoute\Dispatcher::FOUND:
          list($this->class, $this->method) = explode('::', $this->route[1]);
          $this->params = $this->route[2];
          break;
        default:
          $this->class = '\\M\\Controller\\NoRouteFound';
      }
      if(!isset($this->method) || empty($this->method)) {
        $this->method = 'index';
      }
    }

    private function invoke() {
      $controller = new $this->class;
      $controller->before();
      $controller->{$this->method}($this->params);
      $controller->after();
    }

    private function render() {
      $basename = explode('\\', $this->class);
      $basename = end($basename);
      $controller = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $basename));
      
      View::assign('params', $this->params);
      View::assign('body', View::fetch('Controller/'. $controller .'/'. ucfirst($this->method)));
      View::display('Main');
    }
  }
