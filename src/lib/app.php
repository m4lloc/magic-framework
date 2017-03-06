<?php

  namespace Magic;

  class App {

    public static $VENDOR_DIR = '';

    public function __construct() {
      self::$VENDOR_DIR = dirname(dirname(dirname(dirname(dirname(__FILE__))))); // PHP7: $vendor_dir = dirname(__FILE__, 5);
    }

    public function run() {
      $this->environment();
      $this->initializers();
      $this->routes();
      $this->dispatcher();
    }

    private function environment() {
      $dotenv = new \Dotenv\Dotenv(getcwd() .'/..');
      $dotenv->load();
    }

    private function initializers() {
      $init = new Initializer;
      $init->load();
    }

    private function routes() {
      $router = new Router;
      $router->load();
    }

    private function dispatcher() {
      $dispatcher = new Dispatcher;
      $dispatcher->load();
    }
  }
