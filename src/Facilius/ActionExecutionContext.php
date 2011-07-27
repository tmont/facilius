<?php

	namespace Facilius;

	class ActionExecutionContext {
		public $request;
		public $session;
		public $routeMatch;
		/**
		 * @var \Facilius\ModelBinderRegistry
		 */
		public $modelBinders;
		/**
		 * @var string
		 */
		public $action;

		/**
		 * @var Route[]
		 */
		public $routes;

		public function __construct(Request $request, array $session, array $routes, RouteMatch $routeMatch, ModelBinderRegistry $binderRegistry, $action) {
			$this->request = $request;
			$this->session = $session;
			$this->routeMatch = $routeMatch;
			$this->modelBinders = $binderRegistry;
			$this->action = $action;
			$this->routes = $routes;
		}
	}

?>