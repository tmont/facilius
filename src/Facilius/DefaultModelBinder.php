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

			//normal arrays
			if ($context->type === 'array') {
				$value = $context->getValue($context->parameter->getName());
				//avoid unnecessary casting
				return is_array($value) ? $value : (array)$value;
			}

			//typed arrays
			if (isset($context->type[1]) && strrpos($context->type, '[]') === strlen($context->type) - 2) {
				return $this->bindTypedArray($context);
			}

			//if it's a complex type, then we need to match property names, and recursively call bind model

			return null;
		}

		/**
		 * @param BindingContext $context
		 * @return bool|float|int|null|string
		 */
		private function bindSimpleModel(BindingContext $context) {
			$value = $context->getValue($context->parameter->getName());

			if (is_array($value)) {
				$value = (string)$value;
			}

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

		private function bindTypedArray(BindingContext $context) {
			$arrayType = substr($context->type, 0, -2);
			$arrayValueBinder = @$context->actionContext->modelBinders[$arrayType] ?: $this;

			$name = $context->parameter->getName();
			$values = $context->getValue($name);
			if (!is_array($values)) {
				$values = (array)$values;
			}

			$returnValue = array();
			foreach ($values as $value) {
				$returnValue[] = $arrayValueBinder->bindModel(new BindingContext(array($name => $value), $context->actionContext, $context->parameter, $arrayType));
			}

			return $returnValue;
		}

	}
	
?>