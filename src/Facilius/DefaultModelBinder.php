<?php

	namespace Facilius;

	use ReflectionClass, ReflectionProperty;


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
				$value = $context->getValue($context->name);
				//avoid unnecessary casting
				return is_array($value) ? $value : (array)$value;
			}

			//typed arrays
			if (ReflectionUtil::isStronglyTypedArray($context->type)) {
				return $this->bindTypedArray($context);
			}

			return $this->bindComplexModel($context);
		}

		private function bindSimpleModel(BindingContext $context) {
			$value = $context->getValue($context->name);

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

			$name = $context->name;
			$values = $context->getValue($name);
			if (!is_array($values)) {
				$values = (array)$values;
			}

			$returnValue = array();
			foreach ($values as $value) {
				$returnValue[] = $arrayValueBinder->bindModel(new BindingContext(array($name => $value), $context->actionContext, $context->name, $arrayType));
			}

			return $returnValue;
		}

		private function bindComplexModel(BindingContext $context) {
			$type = $context->type;
			if (!class_exists($type)) {
				return null;
			}

			$class = new ReflectionClass($type);
			$ctor = $class->getConstructor();

			if ($ctor && count($ctor->getParameters()) > 0) {
				//constructor has parameters, so we can't instantiate this without some inside knowledge
				return null;
			}

			$object = $class->newInstance();
			$properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);

			$values = $context->values;
			foreach ($properties as $property) {
				$name = $property->getName();
				$propertyType = ReflectionUtil::getPropertyType($property, $nullable);
				$propertyBinder = @$context->actionContext->modelBinders[$propertyType] ?: $this;

				$potentialValues = $values;
				if (ReflectionUtil::isComplexType($propertyType)) {
					$lowerName = strtolower($name);

					foreach ($potentialValues as $key => $value) {
						if (strpos(strtolower($key), $lowerName . '.') === 0) {
							$potentialValues[substr($key, strlen($lowerName) + 1)] = $value;
						} else {
							unset($potentialValues[$key]);
						}
					}
				}

				$newContext = new BindingContext($potentialValues, $context->actionContext, $name, $propertyType);
				$property->setValue($object, $propertyBinder->bindModel($newContext));
			}

			return $object;
		}

	}
	
?>