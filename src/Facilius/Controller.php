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

			foreach ($refParams as $param) {
				$type = ReflectionUtil::getParameterType($param);
				$binder = @$context->modelBinders[$type] ?: new DefaultModelBinder();
				$bindingContext = new BindingContext($context, $param, $type);
				$params[$param->getPosition()] = $binder->bindModel($bindingContext);
			}
			

			return $method->invokeArgs($this, $params);
		}

	}

?>