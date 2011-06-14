<?php

	namespace Facilius;

	use ReflectionMethod, ReflectionParameter, Reflector;

	abstract class Controller {

		public final function execute($action, ActionExecutionContext $context) {
			if (!method_exists($this, $action)) {
				throw new UnknownActionException(get_class($this), $action);
			}

			$method = new ReflectionMethod($this, $action);
			$refParams = $method->getParameters();

			//create parameters for action, i.e. model binding

			$params = array();
			$requestValues = $context->request->queryString->toArray() + $context->request->post->toArray();

			foreach ($refParams as $param) {
				$type = ReflectionUtil::getParameterType($param);
				$binder = @$context->modelBinders[$type] ?: new DefaultModelBinder();
				$params[$param->getPosition()] = $binder->bindModel(new BindingContext($requestValues, $context, $param, $type));
			}

			return $method->invokeArgs($this, $params);
		}

	}

?>