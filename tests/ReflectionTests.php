<?php

	namespace Tmont\Facilius\Tests;

	use PHPUnit_Framework_TestCase, ReflectionClass;
	use Tmont\Facilius\ReflectionUtil;

	class ReflectionTests extends PHPUnit_Framework_TestCase {

		/**
		 * @var \ReflectionClass
		 */
		private $class;

		public function __construct() {
			$this->class = new ReflectionClass('Tmont\Facilius\Tests\RefTest');
		}

		public function testGetParameterTypeFromDocComment() {
			$params = $this->class->getMethod('foo')->getParameters();
			self::assertEquals('double', ReflectionUtil::getParameterType($params[0]));
		}

		public function testDefaultParameterTypeIsString() {
			$params = $this->class->getMethod('foo')->getParameters();
			self::assertEquals('string', ReflectionUtil::getParameterType($params[1]));
		}

		public function testDetectArrayParameterType() {
			$params = $this->class->getMethod('foo')->getParameters();
			self::assertEquals('array', ReflectionUtil::getParameterType($params[2]));
		}

		public function testDetectTypeHintedParameterTypes() {
			$params = $this->class->getMethod('foo')->getParameters();
			self::assertEquals('ReflectionClass', ReflectionUtil::getParameterType($params[3]));
		}

	}

	class RefTest {
		/**
		 * @param double $d
		 */
		public function foo($d, $s, array $arr, ReflectionClass $refClass) {

		}
	}

?>