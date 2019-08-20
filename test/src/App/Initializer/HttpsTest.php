<?php

  class HttpsTest extends \M\TestHelper {

    public function testRedirectToHttpsWhenHttpIsUsed() {
      $this->assertFalse($this->isLocationHeaderSet(), 'It seems the HTTPS location header redirect is already set');
      $i = new \M\Initializer\Https();
      $i->init();
      $this->assertTrue($this->isLocationHeaderSet(), 'Failed to set the HTTPS location header redirect');
    }

    private function isLocationHeaderSet() : bool {
      $h = xdebug_get_headers();
      if(in_array('Location: https://localhost/test', $h)) {
        return true;
      }
      return false;
    }
  }
