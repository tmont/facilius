<?php

	namespace Facilius;

	use ReflectionMethod, ReflectionParameter, Reflector;

	abstract class Controller {

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
					$params[$param->getPosition()] = $binder->bindModel(new BindingContext($requestValues, $context, $param->getName(), $type));
				}

				return $method->invokeArgs($this, $params);
			}

			return $method->invoke($this);
		}

		protected function handleUnknownAction(ActionExecutionContext $context) {
			throw new UnknownActionException(get_class($this), $context->action);
		}

	}

?>