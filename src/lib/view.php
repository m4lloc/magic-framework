<?php

  namespace Magic;

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

		public static function instance() {
			if(!isset(self::$instance)) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public static function set_template_dir($path) {
			return self::instance()->smarty->setTemplateDir($path);
		}

		public static function set_compile_dir($path) {
			return self::instance()->smarty->setCompileDir($path);
		}

		public static function assign($name, $value) {
			return self::instance()->smarty->assign($name, $value);
		}

		public static function fetch($tpl) {
			return self::instance()->smarty->fetch($tpl .'.tpl');
		}

		public static function display($tpl) {
			return self::instance()->smarty->display($tpl .'.tpl');
		}
	}
