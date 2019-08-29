<?php

  namespace M;

  use PHPUnit\Framework\TestCase;

  abstract class TestHelper extends \PHPUnit\Framework\TestCase {
    
    public function isClassLoaded(string $class) : bool {
      return in_array($class, $this->getLoadedClasses());
    }

    public function isNamespaceLoaded(string $namespace) : bool {
      $cs = $this->getLoadedClasses();
      foreach($cs as $c) {
        if(strpos($c, $namespace) !== false) {
          return true;
        }
      }
      return false;
    }

    public function getLoadedClasses() : array {
      return array_filter(
        get_declared_classes(),
        function($className) {
          return !call_user_func(
            array(new \ReflectionClass($className), 'isInternal')
          );
        }
      );
    }
  }