<?php

  /*****************************************************
   * This object is necessary for the decorator
   * pattern. The \Magic\Controller\Base\<class>
   * contains the default functionality if existing.
   *
   * When the user creates an trait with the namespace
   * __NAMESPACE__ and name __CLASS__Decorator it will
   * overwrite the existing methods in the base class.
   *****************************************************
   *
   * namespace __NAMESPACE__;
   *
   * trait __CLASS__Decorator {
   *
   * }
   *
   */

  namespace M\Controller;

  class PageNotFound extends \M\Controller\Base\PageNotFound {
    use PageNotFoundDecorator;
  }
