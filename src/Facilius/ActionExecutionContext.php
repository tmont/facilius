<?php

	namespace Facilius;

	class ActionExecutionContext {
		public $request;
		public $routeMatch;
		/**
		 * @var \Facilius\ModelBinderRegistry
		 */
		public $modelBinders;
		/**
		 * @var string
		 */
		public $action;

		public function __construct(Request $request, RouteMatch $routeMatch, ModelBinderRegistry $binderRegistry, $action) {
			$this->request = $request;
			$this->routeMatch = $routeMatch;
			$this->modelBinders = $binderRegistry;
			$this->action = $action;
		}
	}

?>