<?php

	namespace Tmont\Facilius;

	class ActionResultContext {

		public $action;
		public $request;
		public $response;
		public $routeMatch;
		public $controller;
		public $routes;

		public function __construct($action, Request $request, Response $response, RouteMatch $routeMatch, Controller $controller, array $routes) {
			$this->action = $action;
			$this->request = $request;
			$this->response = $response;
			$this->routeMatch = $routeMatch;
			$this->controller = $controller;
			$this->routes = $routes;
		}

	}

?>