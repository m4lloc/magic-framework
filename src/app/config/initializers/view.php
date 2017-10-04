<?php

  clearstatcache();

  \Magic\View::set_template_dir([
    '../app/view',
    '../vendor/m4lloc/magic-framework/src/app/view'
  ]);
  // \Magic\view::set_compile_dir('../tmp/compile');
