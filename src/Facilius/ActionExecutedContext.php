<?php

	namespace Facilius;

	use ReflectionMethod;

	class ActionExecutedContext {
		/**
		 * @var \Facilius\Request
		 */
		public $request;
		/**
		 * @var \Facilius\RouteMatch
		 */
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
		 * @var \ReflectionMethod
		 */
		public $actionMethod;

		/**
		 * @var \Facilius\ActionResult
		 */
		public $actionResult;

		public function __construct(ActionExecutionContext $context, ActionResult $actionResult, ReflectionMethod $actionMethod) {
			$this->request = $context->request;
			$this->routeMatch = $context->routeMatch;
			$this->modelBinders = $context->modelBinders;
			$this->action = $context->action;
			$this->actionMethod = $actionMethod;
			$this->actionResult = $actionResult;
		}
	}

?>