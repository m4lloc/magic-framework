<?php

  namespace M\Initializer;

  class View extends \M\Initializer {

    public function init() {
      clearstatcache(); // @TODO is this one still required?
      \M\View::set_template_dir('../view');
      \M\View::set_compile_dir('/tmp/magic/compile');
    }
  }
