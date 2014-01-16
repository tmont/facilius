<?php

	namespace Tmont\Facilius;

	use ReflectionParameter, ReflectionProperty, ReflectionMethod;

	class ReflectionUtil {

		const DEFAULT_TYPE = 'string';

		private static $docCommentCache = array();

		/**
		 * @return string|null
		 */
		public static function getRequestMethod(ReflectionMethod $method) {
			$values = self::getDocCommentValues($method);
			$requestMethod = @$values['request-method'];
			if (!$requestMethod) {
				return null;
			}

			//return the last @request-method annotation
			return end($requestMethod);
		}

		/**
		 * @param \ReflectionProperty $property
		 * @param bool $nullable
		 * @return string|null
		 */
		public static function getPropertyType(ReflectionProperty $property, &$nullable) {
            $values = self::getDocCommentValues($property);
            $nullable = false;

            if (isset($values['nullable'])) {
                $nullable = true;
            }
            if (isset($values['var'])) {
                return $values['var'][0];
            }

            return self::DEFAULT_TYPE;
        }

		/**
		 * @param \ReflectionParameter $parameter
		 * @return string
		 */
        public static function getParameterType(ReflectionParameter $parameter) {
            if ($parameter->isArray()) {
                return 'array';
            } else if ($class = $parameter->getClass()) {
                return $class->getName();
            }

            $docValues = self::getDocCommentValues($parameter);
            if (isset($docValues['param'])) {
                foreach ($docValues['param'] as $param) {
                    @list($type, $name) = preg_split('/\s+/', $param, -1, PREG_SPLIT_NO_EMPTY);
                    if (isset($name) && ltrim($name, '$') === $parameter->getName()) {
                        return $type;
                    }
                }
            }

            return self::DEFAULT_TYPE;
        }

		/**
		 * @param object $reflector
		 * @return array
		 */
		public static function getDocCommentValues($reflector) {
            if ($reflector instanceof ReflectionParameter) {
                //the documentation for parameters is in the function documentation (e.g. @param)
                $reflector = $reflector->getDeclaringFunction();
            }

            if (!method_exists($reflector, 'getDocComment')) {
                return array();
            }

            $doc = $reflector->getDocComment();
            if (empty($doc)) {
                //no need to do any regex matching
                return array();
            }

			if (isset(self::$docCommentCache[$doc])) {
				return self::$docCommentCache[$doc];
			}

            preg_match_all('/^[\s\*]*@(.+?)(?:\s|$)(?:\s*)?(.+)?/m', $doc, $values);
            if (!isset($values[2]) || empty($values[2])) {
                //no matches
                return array();
            }

            return (self::$docCommentCache[$doc] = self::combineDocCommentValues($values[1], $values[2]));
        }

		private static function combineDocCommentValues(array $keys, array $values) {
            $combined = array();
            foreach ($keys as $i => $key) {
                if (isset($values[$i])) {
                    $combined[$key][] = $values[$i];
                }
            }

            return $combined;
        }

		/**
		 * @param string $type
		 * @return bool
		 */
		public static function isSimpleType($type) {
            return in_array(strtolower($type), array('int', 'integer', 'bool', 'boolean', 'string', 'double', 'float', 'null'));
        }

		/**
		 * @param string $type
		 * @return bool
		 */
		public static function isComplexType($type) {
			return !self::isSimpleType($type) && $type !== 'array' && !self::isStronglyTypedArray($type);
		}

		/**
		 * @param string $type
		 * @return bool
		 */
		public static function isStronglyTypedArray($type) {
			return isset($type[1]) && strrpos($type, '[]') === strlen($type) - 2;
		}

		/**
		 * @param string $type
		 * @return bool
		 */
        public static function isBuiltInType($type) {
            return self::isSimpleType($type) || in_array(strtolower($type), array('array', 'resource'));
        }
	}
	
?>