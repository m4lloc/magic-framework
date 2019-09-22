<?php

  namespace M;

  abstract class Controller {

    public function before() { }

    public function after() { }

    public function index($params = []) {
      return false;
    }

    public function show($params = []) {
      return false;
    }

    public function edit($params = []) {
      return false;
    }

    public function update($params = []) { }
    
    public function __xhr() {
      return false;
    }

    public function __post() {
      return false;
    }

    public function __set(string $name, mixed $value=null) {
      View::assign($name, $value);
    }

		public function &__get(string $name) {
			View::assigned($name);
    }
  }
