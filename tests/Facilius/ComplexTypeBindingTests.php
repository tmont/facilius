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
	use Facilius\ModelBinderRegistry;


	class ComplexTypeBindingTests extends PHPUnit_Framework_TestCase {
		/**
		 * @var \Facilius\DefaultModelBinder
		 */
		private $binder;

		public function setUp() {
			$this->binder = new DefaultModelBinder();
		}

		private function createContext(array $values, $type, $name) {
			return new BindingContext($values, new ActionExecutionContext(new Request(), new RouteMatch(new Route(''), array()), new ModelBinderRegistry(), ''), $name, $type);
		}

		public function testBindObject() {
			$model = $this->binder->bindModel($this->createContext(array('foo' => '70', 'bar' => 'lulz'), '\Facilius\Tests\TestObject', 'asdf'));

			self::assertNotNull($model);
			self::assertInstanceOf('\Facilius\Tests\TestObject', $model);
			self::assertSame(70, $model->foo);
			self::assertSame('lulz', $model->bar);
		}

		public function testBindObjectWithNestedObjects() {
			$model = $this->binder->bindModel($this->createContext(array('outer.inner.foo' => '70', 'outer.inner.bar' => 'lulz', 'outer.lol2' => 'lolz', 'lol' => 'oh hai!'), '\Facilius\Tests\TestObject3', 'does not matter'));

			self::assertNotNull($model);
			self::assertInstanceOf('\Facilius\Tests\TestObject3', $model);
			self::assertSame('oh hai!', $model->lol);

			self::assertNotNull($model->outer);
			self::assertInstanceOf('\Facilius\Tests\TestObject2', $model->outer);
			self::assertSame('lolz', $model->outer->lol2);

			self::assertNotNull($model->outer->inner);
			self::assertInstanceOf('\Facilius\Tests\TestObject', $model->outer->inner);

			self::assertSame(70, $model->outer->inner->foo);
			self::assertSame('lulz', $model->outer->inner->bar);
		}

	}

	class TestObject {
		/**
		 * @var int
		 */
		public $foo;
		public $bar;
	}

	class TestObject2 {
		/**
		 * @var \Facilius\Tests\TestObject
		 */
		public $inner;

		public $lol2;
	}

	class TestObject3 {
		/**
		 * @var \Facilius\Tests\TestObject2
		 */
		public $outer;

		public $lol;
	}

?>