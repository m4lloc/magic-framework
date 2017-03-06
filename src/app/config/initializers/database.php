<?php

  class database extends \Magic\Initializer {

    public function init() {
      \Magic\Model::connect(
        $database['mysqli.user'],
        $database['mysqli.pass'],
        $database['mysqli.db'],
        $database['mysqli.host']
      );
    }
  }
