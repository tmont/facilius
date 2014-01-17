<?php

	namespace Tmont\Facilius\Tests;

	use PHPUnit_Framework_TestCase;
	use PHPUnit_Framework_Assert;
	use Tmont\Facilius\Controller;
	use Tmont\Facilius\Request;
	use Tmont\Facilius\RouteMatch;
	use Tmont\Facilius\Route;
	use Tmont\Facilius\ActionExecutionContext;
	use Tmont\Facilius\ContentResult;
	use Tmont\Facilius\ModelBinderRegistry;
	use Tmont\Facilius\BindingContext;

//	class ControllerTests extends PHPUnit_Framework_TestCase {
//
//		public function testExecuteActionWithNoParameters() {
//			$controller = new FakeController1();
//			$context = new ActionExecutionContext(new Request(), array(), array(), new RouteMatch(new Route(''), array()), new ModelBinderRegistry(), 'noParams');
//			$result = $controller->execute($context);
//
//			self::assertNotNull($result);
//			self::assertInstanceOf('\Tmont\Facilius\ContentResult', $result);
//			self::assertEquals('no params was executed', $result->getData());
//		}
//
//		public function testExecuteActionWithParameters() {
//			$controller = new FakeController1();
//
//			$binder = $this->getMock('\Tmont\Facilius\ModelBinder', array('bindModel'));
//			$binder
//				->expects($this->at(0))
//				->method('bindModel')
//				->will($this->returnCallback(function(BindingContext $context) {
//					PHPUnit_Framework_Assert::assertEquals('foo', $context->name);
//					PHPUnit_Framework_Assert::assertEquals('string', $context->type);
//					PHPUnit_Framework_Assert::assertSame(array('foo' => 'baz', 'bar' => 'bat'), $context->values);
//					return 'foo';
//				}));
//
//			$binder
//				->expects($this->at(1))
//				->method('bindModel')
//				->will($this->returnCallback(function(BindingContext $context) {
//					PHPUnit_Framework_Assert::assertEquals('bar', $context->name);
//					PHPUnit_Framework_Assert::assertEquals('array', $context->type);
//					PHPUnit_Framework_Assert::assertSame(array('foo' => 'baz', 'bar' => 'bat'), $context->values);
//					return array();
//				}));
//
//			$binders = new ModelBinderRegistry();
//			$binders->setDefaultBinder($binder);
//			$context = new ActionExecutionContext(
//				new Request(array('foo' => 'bar'), array('foo' => 'baz', 'bar' => 'bat')),
//				array(),
//				array(),
//				new RouteMatch(new Route(''), array()),
//				$binders,
//				'hasParams'
//			);
//
//			$result = $controller->execute($context);
//
//			self::assertNotNull($result);
//			self::assertInstanceOf('\Tmont\Facilius\ContentResult', $result);
//			self::assertEquals('has params was executed', $result->getData());
//		}
//
//		public function testExecuteUnknownActionThrowsException() {
//			$this->setExpectedException('\Tmont\Facilius\UnknownActionException');
//			$controller = new FakeController1();
//			$context = new ActionExecutionContext(
//				new Request(),
//				array(),
//				array(),
//				new RouteMatch(new Route(''), array()),
//				new ModelBinderRegistry(),
//				'non existent'
//			);
//			$controller->execute($context);
//		}
//
//		public function testShouldRespectRequestMethodAnnotation() {
//			$this->setExpectedException('\Tmont\Facilius\UnknownActionException');
//			$controller = new FakeController1();
//			$context = new ActionExecutionContext(
//				new Request(),
//				array(),
//				array(),
//				new RouteMatch(new Route(''), array()),
//				new ModelBinderRegistry(),
//				'postOnly'
//			);
//			$controller->execute($context);
//		}
//
//	}
//
//	class FakeController1 extends Controller {
//		public function noParams() {
//			return new ContentResult('no params was executed');
//		}
//
//		public function hasParams($foo, array $bar) {
//			return new ContentResult('has params was executed');
//		}
//
//		/**
//		 * @request-method post
//		 */
//		public function postOnly() {
//
//		}
//	}

?>