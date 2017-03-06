<?php

  namespace Magic;

  class Initializer {

    public static $before = '';

    private $initializers = [];

    public function init() {
      trigger_error('Every initializer should implement it\'s own init method' , E_USER_WARNING);
    }

    public function load() {
      $this->retrieve();
      $this->require_files();
      $this->sort();
      $this->run();
    }

    private function retrieve() {
      $this->initializers = glob('{'. App::$VENDOR_DIR .'/../app/config/initializers/*.php,'. App::$VENDOR_DIR .'/*/*/src/app/config/initializers/*.php}', GLOB_BRACE);
    }

    private function require_files() {
      $initializers = [];
      foreach($this->initializers as $initializer_file) {
        if(file_exists($initializer_file)) {
          require_once $initializer_file;
          $class_name = basename($initializer_file, '.php');
          if(class_exists($class_name)) {
            $initializers[] = $class_name;
          }
        }
      }
      $this->initializers = $initializers;
    }

    private function sort() {
      $inits_by_order = [];
      foreach($this->initializers as $key => $initializer) {
        $index = array_search($initializer::$before, array_keys($inits_by_order));
        $inits_by_order[$initializer] = $key-(int) $index;
      }
      asort($inits_by_order);
      $this->initializers = array_keys($inits_by_order);
    }

    private function run() {
      foreach($this->initializers as $initializer) {
        $init = new $initializer;
        $init->run();
      }
    }
  }
