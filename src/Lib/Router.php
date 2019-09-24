<?php

  namespace M;

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

    public static function get(string $route_pattern, string $handler) : void {
      self::draw('GET', $route_pattern, $handler);
    }

    public static function post(string $route_pattern, string $handler) : void {
      self::draw('POST', $route_pattern, $handler);
    }

    public static function getDeclaredRoutes() : array {
      return self::$routes;
    }

    private function retrieve() {
      $this->route_files = glob('{'. App::$VENDOR_DIR .'/../src/App/Routes.php,'. App::$VENDOR_DIR .'/*/*/src/App/Routes.php}', GLOB_BRACE);
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

    private function defaults() : void {
      $controllers = glob('{'. App::$VENDOR_DIR .'/../src/App/Controller/*.php,'. App::$VENDOR_DIR .'/*/*/src/App/Controller/*.php}', GLOB_BRACE);
      foreach($controllers as $controller) {
        if(substr($controller, -13) == 'Decorator.php') { continue; }

        $controller = '\\M\\Controller\\'. basename($controller, '.php');
        $path = $this->getPathForController($controller);

        self::get($path, $controller .'::index');
        self::get($path .'/{id:\d+}', $controller .'::show');
        self::post($path  .'/{id:\d+}', $controller .'::update');
      }
    }

    private function getPathForController(string $controller) : string {
      list(, , , $path) = explode('\\', $controller);
      if(property_exists($controller, 'path')) {
        $path = $controller::$path;
      }
      return '/'. rtrim(strtolower($path), '/');
    }
  }
