<?php

	namespace Facilius;

	class DefaultModelBinder implements ModelBinder {

		/**
		 * @param BindingContext $context
		 * @return object|null
		 */
		public function bindModel(BindingContext $context) {
			//if it's a simple type, then we match names
			if (ReflectionUtil::isSimpleType($context->type)) {
				return $this->bindSimpleModel($context);
			}

			//if it's an array, then we wrap it in a collection and handle in a specific fashion

			//if it's a complex type, then we need to match property names, and recursively call bind model

			return null;
		}

		private function bindSimpleModel(BindingContext $context) {
			//find a name in the request that matches the name of the parameter (case insensitive)

			$name = $context->parameter->getName();
			$value = @$context->executionContext->request->post[$name] ?: @$context->executionContext->request->queryString[$name];

			switch ($context->type) {
				case 'int':
				case 'integer':
					return (int)$value;
				case 'bool':
				case 'boolean':
					$value = strtolower($value);
					if ($value === 'true') {
						return true;
					}
					if ($value === 'false') {
						return false;
					}

					return (bool)$value;
				case 'double':
				case 'float':
					return (double)$value;
				case 'null':
					return null;
				default:
					return (string)$value;
			}
		}

	}
	
?>