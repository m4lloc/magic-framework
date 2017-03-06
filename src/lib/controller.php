<?php

  namespace Magic;

  abstract class Controller {

    public function before() { }

    public function after() { }

    public function index() {
      return false;
    }

    public function show() {
      return false;
    }

    public function edit() {
      return false;
    }

    public function __ajax() {
      return false;
    }

    public function __post() {
      return false;
    }
  }
