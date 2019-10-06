<?php

  namespace M\Initializer;

  class Autoload extends \M\Initializer {

    public function init() {
      $paths = array_filter([
        get_include_path(),
        realpath('../src/App'),
        realpath(dirname(__FILE__, 2)),
        realpath('../src/Lib'),
        realpath(dirname(__FILE__, 3) .'/Lib'),
      ]);
      set_include_path(implode(PATH_SEPARATOR, $paths));

      $this->registerAutoloader();
      $this->registerDecoraterLoader();
    }

    private function registerAutoloader() {
      spl_autoload_register(function($namespaced_class) {
        if(class_exists($namespaced_class)) { return true; }

        $path = realpath(\M\App::$VENDOR_DIR .'/../src/App/'. substr(str_replace('\\', '/', $namespaced_class), 2) .'.php');
        if(file_exists($path)) {
          require $path;
        }
      }, true, true);
    }

    private function registerDecoraterLoader() {
      spl_autoload_register(function($namespaced_trait) {
        if(trait_exists($namespaced_trait)) { return true; }
        if(substr($namespaced_trait, -9) != 'Decorator') { return false; }

        if(strpos($namespaced_trait, '\\')) {
          $namespace = explode('\\', strrev($namespaced_trait), 2);
          $namespace = strrev($namespace[1]);
          if(!empty($namespace)) {
            $namespace_create = 'namespace '. $namespace .'; ';
            $trait = str_replace($namespace .'\\', '', $namespaced_trait);
          }
        }

        $trait_path = str_replace('\\', '/', str_replace('M\\', '', $namespace)) .'/'. $trait .'.php';
        $paths = explode(PATH_SEPARATOR, get_include_path());
        foreach($paths as $path) {
          if(file_exists($path .'/'. $trait_path)) {
            require $trait_path;
            return trait_exists($namespaced_trait);
          }
        }

        eval($namespace_create .'trait '. $trait .' { }');
      }, true, true);
    }
  }
