<?php

	namespace Facilius;

	use ReflectionParameter;

	class BindingContext {
		/**
		 * @var \Facilius\Request
		 */
		public $actionContext;
		/**
		 * @var string
		 */
		public $name;
		/**
		 * @var string
		 */
		public $type;

		/**
		 * @var array
		 */
		public $values;

		public function __construct(array $values, ActionExecutionContext $context, $name, $type) {
			$this->actionContext = $context;
			$this->name = $name;
			$this->type = $type;
			$this->values = $values;
		}

		public function getValue($key) {
			return @$this->values[$key];
		}
	}
	
?>