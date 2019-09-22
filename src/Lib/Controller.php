<?php

  namespace M;

  abstract class Controller {

    public function before() { }

    public function after() { }

    public function index(array $params = []) {
      return false;
    }

    public function show(array $params = []) {
      return false;
    }

    public function edit(array $params = []) {
      return false;
    }

    public function update(array $params = []) { }
    
    public function __xhr() {
      return false;
    }

    public function __post() {
      return false;
    }

    public function __set(string $name, $value=null) {
      View::assign($name, $value);
    }

		public function &__get(string $name) {
			View::assigned($name);
    }
  }
