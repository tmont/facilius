<?php

	namespace Facilius;

	use ReflectionParameter;

	class BindingContext {
		/**
		 * @var \Facilius\ActionExecutionContext
		 */
		public $executionContext;
		/**
		 * @var \ReflectionParameter
		 */
		public $parameter;
		/**
		 * @var string
		 */
		public $type;

		public function __construct(ActionExecutionContext $context, ReflectionParameter $parameter, $type) {
			$this->executionContext = $context;
			$this->parameter = $parameter;
			$this->type = $type;
		}
	}
	
?>