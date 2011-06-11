<?php

	namespace Facilius;

	use Exception;

	abstract class WebApplication {
		private $routes = array();

		protected final function registerRoute($pattern, array $defaults = array(), $routeName = null) {
			$this->routes[] = new Route($pattern, $defaults, $routeName);
			return $this;
		}

		protected function onError(Exception $e) {}
		protected function onStart() {}
		protected function onEnd() {}

		public function run(Request $request) {
			try {
				$this->onStart();
				$this->handleRequest($request);
			} catch (Exception $e) {
				$this->onError($e);
			}

			try {
				$this->onEnd();
			} catch (Exception $e) {
				$this->onError($e);
			}
		}

		private function handleRequest(Request $request) {
			
		}

	}

?>