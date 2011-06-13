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
				$params[$param->getPosition()] = $binder->bindModel($type);
			}
			

			return $method->invokeArgs($this, $params);
		}

	}

	class BindingContext {
		public $executionContext;
		public $parameter;
		public $type;

		public function __construct(ActionExecutionContext $context, ReflectionParameter $parameter, $type) {
			$this->executionContext = $context;
			$this->parameter = $parameter;
			$this->type = $type;
		}
	}


	interface ModelBinder {
		/**
		 * @return object
		 */
		function bindModel(BindingContext $context);
	}

	class DefaultModelBinder implements ModelBinder {

		public function bindModel(BindingContext $context) {
			//if it's a simple type, then we match names

			//if it's an array, then we wrap it in a collection and handle in a specific fashion

			//if it's a complex type, then we need to match property names, and recursively call bind model


		}
	}





?>