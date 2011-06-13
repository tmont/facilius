<?php

	namespace Facilius;

	class ActionExecutionContext {
		public $request;
		public $routeMatch;
		/**
		 * @var \Facilius\ModelBinder[]
		 */
		public $modelBinders;

		public function __construct(Request $request, RouteMatch $routeMatch) {
			$this->request = $request;
			$this->routeMatch = $routeMatch;
			$this->modelBinders = array();
		}
	}

?>