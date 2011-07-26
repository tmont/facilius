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

		public function __construct(Request $request, array $session, RouteMatch $routeMatch, ModelBinderRegistry $binderRegistry, $action) {
			$this->request = $request;
			$this->session = $session;
			$this->routeMatch = $routeMatch;
			$this->modelBinders = $binderRegistry;
			$this->action = $action;
		}
	}

?>