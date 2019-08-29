<?php

  class ErrorTest extends \M\TestHelper {

    public function testWhenDsnIsGivenEnableSentry() {
      putenv('SENTRY_DSN=https://key@sentry.io/project');
      $i = new \M\Initializer\Error();
      $i->init();
      $this->assertTrue($this->isSentryEnabled(), 'Sentry is not loaded while it should have been');
    }

    public function testWhenDsnIsEmptyDoNotEnableSentry() {
      putenv('SENTRY_DSN=');
      $i = new \M\Initializer\Error();
      $i->init();
      $this->assertFalse($this->isSentryEnabled(), 'Sentry is loaded even when the DSN is empty');
    }

    private function isSentryEnabled() : bool {
      if($this->isClassLoaded('Sentry\Client')) {
        return true;
      }
      return false;
    }
  }
