<?php

	namespace Tmont\Facilius\Tests;

	use PHPUnit_Framework_TestCase;
	use ReflectionClass, ReflectionParameter;
	use Tmont\Facilius\DefaultModelBinder;
	use Tmont\Facilius\BindingContext;
	use Tmont\Facilius\ActionExecutionContext;
	use Tmont\Facilius\Request;
	use Tmont\Facilius\RouteMatch;
	use Tmont\Facilius\Route;
	use Tmont\Facilius\ModelBinderRegistry;

	class SimpleTypeBindingTests extends PHPUnit_Framework_TestCase {
		/**
		 * @var \Tmont\Facilius\DefaultModelBinder
		 */
		private $binder;
		
		public function setUp() {
			$this->binder = new DefaultModelBinder();
		}

		private function createContext(array $values, $type) {
			$context = new ActionExecutionContext(
				new Request(),
				array(),
				array(),
				new RouteMatch(new Route(''), array()),
				new ModelBinderRegistry(),
				''
			);
			return new BindingContext($values, $context, 'a', $type);
		}

		public function testBindDouble() {
			self::assertSame(17.4, $this->binder->bindModel($this->createContext(array('a' => '17.4'), 'double')));
		}

		public function testBindFloat() {
			self::assertSame(17.4, $this->binder->bindModel($this->createContext(array('a' => '17.4'), 'float')));
		}

		public function testBindFloatFromStringIsZero() {
			self::assertSame(0.0, $this->binder->bindModel($this->createContext(array('a' => 'asdf'), 'double')));
			self::assertSame(0.0, $this->binder->bindModel($this->createContext(array('a' => 'asdf'), 'float')));
		}

		public function testBindString() {
			self::assertSame('asdf', $this->binder->bindModel($this->createContext(array('a' => 'asdf'), 'string')));
		}

		public function testBindBooleanFromBooleanString() {
			self::assertSame(false, $this->binder->bindModel($this->createContext(array('a' => 'false'), 'bool')));
			self::assertSame(false, $this->binder->bindModel($this->createContext(array('a' => 'false'), 'boolean')));
			self::assertSame(true, $this->binder->bindModel($this->createContext(array('a' => 'true'), 'bool')));
			self::assertSame(true, $this->binder->bindModel($this->createContext(array('a' => 'true'), 'boolean')));
		}

		public function testBindBooleanFromInteger() {
			self::assertSame(false, $this->binder->bindModel($this->createContext(array('a' => '0'), 'bool')));
			self::assertSame(true, $this->binder->bindModel($this->createContext(array('a' => '1'), 'bool')));
			self::assertSame(true, $this->binder->bindModel($this->createContext(array('a' => '984'), 'bool')));
		}

		public function testBindBooleanFromNullIsFalse() {
			self::assertSame(false, $this->binder->bindModel($this->createContext(array('a' => null), 'bool')));
		}

		public function testBindBooleanFromEmptyStringIsFalse() {
			self::assertSame(false, $this->binder->bindModel($this->createContext(array('a' => ''), 'bool')));
		}

		public function testBindBooleanFromNonEmptyStringIsTrue() {
			self::assertSame(true, $this->binder->bindModel($this->createContext(array('a' => 'asdf'), 'bool')));
		}

		public function testBindInteger() {
			self::assertSame(12, $this->binder->bindModel($this->createContext(array('a' => '12'), 'int')));
			self::assertSame(12, $this->binder->bindModel($this->createContext(array('a' => '12'), 'integer')));
		}

		public function testBindNegativeInteger() {
			self::assertSame(-12, $this->binder->bindModel($this->createContext(array('a' => '-12'), 'int')));
			self::assertSame(-12, $this->binder->bindModel($this->createContext(array('a' => '-12'), 'integer')));
		}

		public function testBindIntegerFromStringIsZero() {
			self::assertSame(0, $this->binder->bindModel($this->createContext(array('a' => 'asdf'), 'int')));
		}

		public function testBindNull() {
			self::assertSame(null, $this->binder->bindModel($this->createContext(array('a' => 'asdf'), 'null')));
		}

	}

	class ObjectToBind {
		public function foo($a) {

		}
	}

?>