<?php

	namespace Facilius;

	use ReflectionParameter;

	class BindingContext {
		/**
		 * @var \Facilius\Request
		 */
		public $actionContext;
		/**
		 * @var \ReflectionParameter
		 */
		public $parameter;
		/**
		 * @var string
		 */
		public $type;

		private $values;

		public function __construct(array $values, ActionExecutionContext $context, ReflectionParameter $parameter, $type) {
			$this->actionContext = $context;
			$this->parameter = $parameter;
			$this->type = $type;
			$this->values = $values;
		}

		public function getValue($key) {
			return @$this->values[$key];
		}
	}
	
?>