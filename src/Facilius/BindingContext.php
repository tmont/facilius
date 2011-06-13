<?php

	namespace Facilius;

	use ReflectionParameter;

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
	
?>