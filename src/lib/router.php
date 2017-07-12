<?php

  namespace Magic;

  class Router  {

    public static $routes = [];

    private $route_files = [];

    public function load() {
      $this->retrieve();
      $this->require_files();
      $this->defaults();
    }

    public static function draw($method, $route_pattern, $handler) {
      $route = [
        'method' => $method,
        'route_pattern' => $route_pattern,
        'handler' => $handler
      ];
      if(!self::exists($route)) {
        self::$routes[] = $route;
      }
    }

    public static function get($route_pattern, $handler) {
      self::draw('GET', $route_pattern, $handler);
    }

    public static function post($route_pattern, $handler) {
      self::draw('POST', $route_pattern, $handler);
    }

    private function retrieve() {
      $this->route_files = glob('{'. App::$VENDOR_DIR .'/../app/config/routes.php,'. App::$VENDOR_DIR .'/*/*/src/app/config/routes.php}', GLOB_BRACE);
    }

    private static function exists($route) {
      return in_array($route, self::$routes);
    }

    private function require_files() {
      foreach($this->route_files as $routes) {
        if(file_exists($routes)) {
          require_once $routes;
        }
      }
    }

    private function defaults() {
      Router::draw(['GET','POST'], '/', '\\Magic\\Controller\\Homepage');

      $controllers = glob('{'. App::$VENDOR_DIR .'/../app/controllers/*.php,'. App::$VENDOR_DIR .'/*/*/src/app/controllers/*.php}', GLOB_BRACE);
      foreach($controllers as $controller) {
        $controller = basename($controller, '.php');

        // Router::get('/'. $controller, '\\Magic\\Controller\\'. ucfirst($controller) .'::index');
        // Router::get('/'. $controller .'/{id:\d+}', '\\Magic\\Controller\\'. ucfirst($controller) .'::index');
        // Router::post('/'. $controller  .'/{id:\d+}', '\\Magic\\Controller\\'. ucfirst($controller) .'::index');
      }
    }
  }
