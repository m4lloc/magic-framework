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
      $controllers = glob('{'. App::$VENDOR_DIR .'/../app/controller/*.php,'. App::$VENDOR_DIR .'/*/*/src/app/controller/*.php}', GLOB_BRACE);
      foreach($controllers as $controller) {
        $basename = strtolower(basename($controller, '.php'));
        $path = str_replace('_', '-', $basename);
        $controller = '\\Magic\\Controller\\'. str_replace(' ', '', ucwords(str_replace('_', ' ', $basename)));

        self::get('/'. $path, $controller .'::index');
        self::get('/'. $path .'/{id:\d+}', $controller .'::show');
        self::post('/'. $path  .'/{id:\d+}', $controller .'::update');
      }
      self::draw(['GET','POST'], '/', '\\Magic\\Controller\\Homepage');
    }
  }
