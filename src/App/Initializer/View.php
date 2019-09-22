<?php

  namespace M\Initializer;

  class View extends \M\Initializer {

    public function init() {
      clearstatcache(); // @TODO is this one still required?
      \M\View::addTemplateDirs([
        '../src/App/View',
        // '../vendor/m4lloc/magic-framework/src/App/View',
      ]);
      \M\View::setCompileDir('/tmp/magic/compile');
    }
  }
