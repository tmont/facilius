<?php

	namespace Facilius;

	use ReflectionMethod, Exception;

	abstract class Controller {

		/**
		 * @var ViewLocator
		 */
		private $viewLocator;

		/**
		 * @var ActionExecutionContext
		 */
		private $currentContext;

		public function setViewLocator(ViewLocator $viewLocator) {
			$this->viewLocator = $viewLocator;
		}

		public function getViewLocator() {
			return $this->viewLocator;
		}

		protected final function getContext() {
			return $this->currentContext;
		}

		/**
		 * @param ActionExecutionContext $context
		 * @return ActionResult
		 */
		public final function execute(ActionExecutionContext $context) {
			$this->currentContext = $context;
			
			if (!method_exists($this, $context->action)) {
				return $this->handleUnknownAction($context);
			}

			$method = new ReflectionMethod($this, $context->action);
			$requestMethod = ReflectionUtil::getRequestMethod($method);
			if ($requestMethod && strtolower($context->request->requestMethod) !== strtolower($requestMethod)) {
				//if @request-method annotation exists and does not match the incoming request method, then it's not a match
				return $this->handleUnknownAction($context);
			}

			$refParams = $method->getParameters();

			//create parameters for action, i.e. model binding
			if (count($refParams) > 0) {
				$params = array();
				$requestValues = array_merge($context->routeMatch->getData(), $context->request->queryString->toArray(), $context->request->post->toArray());

				foreach ($refParams as $param) {
					$type = ReflectionUtil::getParameterType($param);
					$params[$param->getPosition()] = $context
						->modelBinders
						->getBinderOrDefault($type)
						->bindModel(new BindingContext($requestValues, $context, $param->getName(), $type));
				}

				return $method->invokeArgs($this, $params);
			}

			return $method->invoke($this);
		}

		protected function handleUnknownAction(ActionExecutionContext $context) {
			throw new UnknownActionException(get_class($this), $context->action);
		}

		protected function view($name = null, $controller = null, $model = null) {
			if (!$this->viewLocator) {
				throw new Exception('View locator has not been set. Please reconfigure your app\'s createController() method.');
			}

			$path = $this->viewLocator->locate($name ?: $this->currentContext->action, $controller ?: $this->getControllerName());
			return new ViewResult(new View($path), $model);
		}

		protected function redirect($url, $httpStatusCode = 302) {
			return new RedirectResult($url, $httpStatusCode);
		}

		public function getControllerName() {
			$parts = explode('\\', get_class($this));
			$controller = end($parts);
			return strtolower(substr($controller, 0, strlen($controller) - 10));
		}

	}

?>