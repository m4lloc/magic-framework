<?php

  namespace M\Initializer;

  class Autoload extends \M\Initializer {

    public function init() {
      set_include_path(implode(PATH_SEPARATOR,[
        get_include_path(),
        realpath('../src/App'),
        realpath('../src/Lib')
      ]));

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
