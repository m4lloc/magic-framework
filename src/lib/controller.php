<?php

  namespace Magic;

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

    public function update($params = []) {
      return false;
    }
  }
