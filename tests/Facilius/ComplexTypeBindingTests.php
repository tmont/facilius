<?php

	namespace Facilius\Tests;

	use PHPUnit_Framework_TestCase;
	use ReflectionClass, ReflectionParameter;
	use Facilius\DefaultModelBinder;
	use Facilius\BindingContext;
	use Facilius\ActionExecutionContext;
	use Facilius\Request;
	use Facilius\RouteMatch;
	use Facilius\Route;

	class ComplexTypeBindingTests extends PHPUnit_Framework_TestCase {
		/**
		 * @var \Facilius\DefaultModelBinder
		 */
		private $binder;
		/**
		 * @var \ReflectionClass
		 */
		private $class;
		/**
		 * @var \ReflectionParameter[]
		 */
		private $params;

		public function __construct() {
			$this->class = new ReflectionClass(__NAMESPACE__ . '\ObjectToBind3');
			$this->params = $this->class->getMethod('foo')->getParameters();
		}

		public function setUp() {
			$this->binder = new DefaultModelBinder();
		}

		private function createContext(array $values, $type) {
			return new BindingContext($values, new ActionExecutionContext(new Request(), new RouteMatch(new Route(''), array())), $this->params[0], $type);
		}

		public function testBindComplexObject() {
			$model = $this->binder->bindModel($this->createContext(array('foo' => '70', 'bar' => 'lulz'), '\Facilius\Tests\TestObject'));

			self::assertNotNull($model);
			self::assertInstanceOf('\Facilius\Tests\TestObject', $model);
			self::assertSame(70, $model->foo);
			self::assertSame('lulz', $model->bar);
		}

	}

	class ObjectToBind3 {
		public function foo(TestObject $foo) {}
	}

	class TestObject {
		/**
		 * @var int
		 */
		public $foo;
		public $bar;
	}

?>