<?php

	namespace Facilius\Example;

	use Facilius\WebApplication;
	use Facilius\Request;
	use Facilius\DefaultViewLocator;
	use Exception;

	require_once '../src/Facilius/bootstrap.php';

	spl_autoload_register(function($className) {
		$file = __DIR__ . '/controllers/' . basename(str_replace('\\', '/', $className)) . '.php';
		if (is_file($file)) {
			require_once $file;
			return true;
		}

		echo $file . '<br />';
		return false;
	});


	class ExampleApp extends WebApplication {

		public function __construct() {
			parent::__construct();
			$this->debugEnabled = true;
		}

		protected function onStart() {
			$this->registerRoute('/(?:(?<controller>.+)(?:/(?<action>.+)(?:/(?<id>.+))?)?)?', array('controller' => 'Home', 'action' => 'Index'), 'default');
		}

		/**
		 * @param string $name
		 * @return Controller
		 */
		protected function createController($name) {
			$class = '\Facilius\Example\Controllers\\' . $name . 'Controller';
			if (!class_exists($class)) {
				throw new Exception('Unable to create controller');
			}

			$controller = new $class();
			$controller->setViewLocator(new DefaultViewLocator(__DIR__));
			return $controller;
		}
	}

	ExampleApp::start();

?>