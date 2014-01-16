<?php

	namespace Tmont\Facilius\Tests;

	use PHPUnit_Framework_TestCase;
	use Tmont\Facilius\DefaultModelBinder;
	use Tmont\Facilius\BindingContext;
	use Tmont\Facilius\ActionExecutionContext;
	use Tmont\Facilius\Request;
	use Tmont\Facilius\RouteMatch;
	use Tmont\Facilius\Route;
	use Tmont\Facilius\ModelBinderRegistry;

	class ArrayBindingTests extends PHPUnit_Framework_TestCase {
		/**
		 * @var \Tmont\Facilius\DefaultModelBinder
		 */
		private $binder;
		
		public function setUp() {
			$this->binder = new DefaultModelBinder();
		}

		private function createContext(array $values, $type) {
			$context = new ActionExecutionContext(new Request(), array(), array(), new RouteMatch(new Route(''), array()), new ModelBinderRegistry(), '');
			return new BindingContext($values, $context, 'a', $type);
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