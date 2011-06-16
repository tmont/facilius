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

	class ArrayBindingTests extends PHPUnit_Framework_TestCase {
		/**
		 * @var \Facilius\DefaultModelBinder
		 */
		private $binder;
		
		public function setUp() {
			$this->binder = new DefaultModelBinder();
		}

		private function createContext(array $values, $type) {
			return new BindingContext($values, new ActionExecutionContext(new Request(), new RouteMatch(new Route(''), array()), new ModelBinderRegistry(), ''), 'a', $type);
		}

		public function testBindDefaultArray() {
			self::assertSame(array('lol'), $this->binder->bindModel($this->createContext(array('a' => array('lol')), 'array')));
		}

		public function testBindDefaultArrayWhenValueIsNotAnArray() {
			self::assertSame(array('lol'), $this->binder->bindModel($this->createContext(array('a' => array('lol')), 'array')));
		}

		public function testBindTypedArray() {
			self::assertSame(array(4), $this->binder->bindModel($this->createContext(array('a' => array('4')), 'int[]')));
		}

		public function testBindTypedTwoDimensionalArray() {
			self::assertSame(array(array(4)), $this->binder->bindModel($this->createContext(array('a' => array(array('4'))), 'int[][]')));
		}

	}

	class ObjectToBind2 {
		public function foo($a) {}
	}

?>