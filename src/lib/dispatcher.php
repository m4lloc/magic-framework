<?php

  namespace Magic;

  class Dispatcher {

    private $route;
    private $class;
    private $method;
    private $params;

    public function load() {
      $this->determine_route();
      $this->determine_controller();
      $this->invoke();
      $this->render();
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

    private function determine_controller() {
      switch($this->route[0]) {
        case \FastRoute\Dispatcher::NOT_FOUND:
          die('404');
          $this->class = '\\Magic\\Controller\\PageNotFound';
          break;
        case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
          die('Not allowed');
          $this->class = '\\Magic\\Controller\\MethodNotAllowed';
          $params = ['allowed_methods' => $this->route[1]];
          break;
        case \FastRoute\Dispatcher::FOUND:
          list($this->class, $this->method) = explode('::', $this->route[1]);
          break;
        default:
          die('No route');
          $this->class = '\\Magic\\Controller\\NoRouteFound';
      }
      if(!isset($this->method) || empty($this->method)) {
        $this->method = 'index';
      }
    }

    private function invoke() {
      $controller = new $this->class;
      // var_dump($controller);
      $controller->before();
      $controller->{$this->method}($this->params);
      $controller->after();
    }

    private function render() {
      $basename = explode('\\', $this->class);
      $basename = end($basename);
      $tpl = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $basename));
      // var_dump($tpl);
      View::assign('params', $this->params);
      View::assign('body', View::fetch('../app/view/controller/'. $tpl .'/'. $this->method));
      View::display('index');
    }
  }
