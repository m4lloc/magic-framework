<?php

  namespace M;

	class View {

		private $smarty = null;
    private static $instance = null;

		public function __set($name, $value) {
			$this->$name = $value;
			$this->assign($name, $this->$name);
		}

		public function &__get($name) {
			return $this->$name;
		}

		public function __construct() {
			if(null === $this->smarty) {
				$this->smarty = new \Smarty();
			}
		}

		public static function instance() : \M\View {
			if(!isset(self::$instance)) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public static function addTemplateDir(string $path) {
			return self::instance()->smarty->addTemplateDir($path);
		}

		public static function addTemplateDirs(array $paths) {
			return self::instance()->smarty->addTemplateDir($paths);
		}

		public static function setCompileDir(string $path) {
			return self::instance()->smarty->setCompileDir($path);
		}

		public static function assign(string $name, $value) {
			return self::instance()->smarty->assign($name, $value);
		}


		public static function assigned(string $name) {
			return self::instance()->smarty->getTemplateVars($name);
		}

		public static function fetch(string $tpl) {
			return self::instance()->smarty->fetch($tpl .'.tpl');
		}

		public static function display(string $tpl) {
			return self::instance()->smarty->display($tpl .'.tpl');
		}
	}
