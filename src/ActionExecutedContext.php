<?php

	namespace Tmont\Facilius;

	use ReflectionMethod;

	class ActionExecutedContext {
		/**
		 * @var \Tmont\Facilius\Request
		 */
		public $request;
		/**
		 * @var \Tmont\Facilius\RouteMatch
		 */
		public $routeMatch;
		/**
		 * @var \Tmont\Facilius\ModelBinderRegistry
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
		 * @var \Tmont\Facilius\ActionResult
		 */
		public $actionResult;

		/**
		 * @var Route[]
		 */
		public $routes;

		public function __construct(ActionExecutionContext $context, ActionResult $actionResult, ReflectionMethod $actionMethod) {
			$this->request = $context->request;
			$this->routeMatch = $context->routeMatch;
			$this->modelBinders = $context->modelBinders;
			$this->routes = $context->routes;
			$this->action = $context->action;
			$this->actionMethod = $actionMethod;
			$this->actionResult = $actionResult;
		}
	}

?>