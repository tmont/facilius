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

	class SimpleTypeBindingTests extends PHPUnit_Framework_TestCase {
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
			$this->class = new ReflectionClass(__NAMESPACE__ . '\ObjectToBind');
			$this->params = $this->class->getMethod('foo')->getParameters();
		}

		public function setUp() {
			$this->binder = new DefaultModelBinder();
		}

		private static function createActionContext(Request $request) {
			return new ActionExecutionContext($request, new RouteMatch(new Route(''), array()));
		}

		public function testBindDouble() {
			$request = new Request(array('a' => '17.4'));
			self::assertSame(17.4, $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'double')));
		}

		public function testBindFloat() {
			$request = new Request(array('a' => '17.4'));
			self::assertSame(17.4, $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'float')));
		}

		public function testBindFloatFromStringIsZero() {
			$request = new Request(array('a' => 'asdf'));
			self::assertSame(0.0, $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'float')));
			self::assertSame(0.0, $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'double')));
		}

		public function testBindString() {
			$request = new Request(array('a' => 'asdf'));
			self::assertSame('asdf', $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'string')));
		}

		public function testBindBooleanFromBooleanString() {
			$request = new Request(array('a' => 'false'));
			self::assertSame(false, $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'bool')));
			self::assertSame(false, $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'boolean')));

			$request = new Request(array('a' => 'true'));
			self::assertSame(true, $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'bool')));
			self::assertSame(true, $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'boolean')));
		}

		public function testBindBooleanFromInteger() {
			$request = new Request(array('a' => '0'));
			self::assertSame(false, $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'bool')));

			$request = new Request(array('a' => '1'));
			self::assertSame(true, $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'bool')));
		}

		public function testBindBooleanFromNullIsFalse() {
			$request = new Request(array('a' => null));
			self::assertSame(false, $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'bool')));
		}

		public function testBindBooleanFromEmptyStringIsFalse() {
			$request = new Request(array('a' => ''));
			self::assertSame(false, $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'bool')));
		}

		public function testBindBooleanFromNonEmptyStringIsTrue() {
			$request = new Request(array('a' => 'asdf'));
			self::assertSame(true, $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'bool')));
		}

		public function testBindInteger() {
			$request = new Request(array('a' => '12'));
			self::assertSame(12, $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'int')));
			self::assertSame(12, $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'integer')));
		}

		public function testBindNegativeInteger() {
			$request = new Request(array('a' => '-12'));
			self::assertSame(-12, $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'int')));
			self::assertSame(-12, $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'integer')));
		}

		public function testBindIntegerFromStringIsZero() {
			$request = new Request(array('a' => 'asdf'));
			self::assertSame(0, $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'int')));
		}

		public function testBindNull() {
			$request = new Request(array('a' => 'asdf'));
			self::assertSame(null, $this->binder->bindModel(new BindingContext(self::createActionContext($request), $this->params[0], 'null')));
		}

	}

	class ObjectToBind {
		public function foo($a) {

		}
	}

?>