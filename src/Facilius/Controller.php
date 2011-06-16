<?php

	namespace Facilius;

	use ReflectionMethod, Exception;

	abstract class Controller {

		/**
		 * @var ViewLocator
		 */
		private $viewLocator;

		public function setViewLocator(ViewLocator $viewLocator) {
			$this->viewLocator = $viewLocator;
		}

		/**
		 * @param ActionExecutionContext $context
		 * @return ActionResult
		 */
		public final function execute(ActionExecutionContext $context) {
			if (!method_exists($this, $context->action)) {
				return $this->handleUnknownAction($context);
			}

			$method = new ReflectionMethod($this, $context->action);
			$refParams = $method->getParameters();


			//create parameters for action, i.e. model binding
			if (count($refParams) > 0) {
				$params = array();
				$requestValues = array_merge($context->request->queryString->toArray(), $context->request->post->toArray());

				foreach ($refParams as $param) {
					$type = ReflectionUtil::getParameterType($param);
					$binder = $context->modelBinders->getBinderOrDefault($type);
					$model = $binder->bindModel(new BindingContext($requestValues, $context, $param->getName(), $type));
					//var_dump($model);
					$params[$param->getPosition()] = $model;
				}

				return $method->invokeArgs($this, $params);
			}

			//echo $method->name . '<br />';
			return $method->invoke($this);
		}

		protected function handleUnknownAction(ActionExecutionContext $context) {
			throw new UnknownActionException(get_class($this), $context->action);
		}

		protected function view($name, $controller = null, $model = null) {
			if (!$this->viewLocator) {
				throw new Exception('View locator has not been set. Please reconfigure your app\'s createController() method.');
			}

			$path = $this->viewLocator->locate($name, $controller ?: $this->getControllerName());
			return new ViewResult(new View($path), $model);
		}

		private function getControllerName() {
			$parts = explode('\\', get_class($this));
			$controller = end($parts);
			return strtolower(substr($controller, 0, strlen($controller) - 10));
		}

	}

?>