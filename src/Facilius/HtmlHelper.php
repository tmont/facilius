<?php

	namespace Facilius;

	class HtmlHelper {

		/**
		 * @var Route[]
		 */
		private $routes;

		public function __construct(array $routes) {
			$this->routes = $routes;
		}

		public function actionLink($action, $controller, array $routeValues = array()) {
			$routeValues['action'] = $action;
			$routeValues['controller'] = $controller;

			foreach ($this->routes as $route) {

			}
		}

	}

?>