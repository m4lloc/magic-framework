<?php

  class database extends \M\Initializer {

    public function init() {
      // @TODO use environment variables
      \M\Model::connect(
        $database['mysqli.user'],
        $database['mysqli.pass'],
        $database['mysqli.db'],
        $database['mysqli.host']
      );
    }
  }
