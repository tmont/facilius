<?php

	namespace Facilius;

	class ActionResultContext {

		public $action;
		public $request;
		public $response;
		public $routeMatch;

		public function __construct($action, Request $request, Response $response, RouteMatch $routeMatch) {
			$this->action = $action;
			$this->request = $request;
			$this->response = $response;
			$this->routeMatch = $routeMatch;
		}

	}

?>