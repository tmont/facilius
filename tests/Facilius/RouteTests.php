<?php

	namespace Facilius\Tests;

	use Facilius\Route;
	use PHPUnit_Framework_TestCase;

	class RouteTests extends PHPUnit_Framework_TestCase {

		private $route;

		public function __construct() {
			$this->route = new Route('{controller}/{action}/{id}', array('controller' => 'home', 'action' => 'index', 'id' => null));
		}

		public function testShouldOmitDefaultValuesWhenGeneratingUrl() {
			self::assertEquals('/', $this->route->generateUrl(array()));
			self::assertEquals('/foo', $this->route->generateUrl(array('controller' => 'foo')));
			self::assertEquals('/foo/bar', $this->route->generateUrl(array('controller' => 'foo', 'action' => 'bar')));
			self::assertEquals('/foo/bar/baz', $this->route->generateUrl(array('controller' => 'foo', 'action' => 'bar', 'id' => 'baz')));
		}

		public function testShouldUseDefaultValuesToFillInBlanks() {
			self::assertEquals('/home/bar', $this->route->generateUrl(array('action' => 'bar')));
			self::assertEquals('/home/index/baz', $this->route->generateUrl(array('id' => 'baz')));
		}

		public function testShouldAppendExtraRouteValuesToQueryString() {
			self::assertEquals('/foo?lol=wut&meh=queef', $this->route->generateUrl(array('controller' => 'foo', 'lol' => 'wut', 'meh' => 'queef')));
		}

		public function testShouldReturnNullIfNoRouteMatches() {
			$route = new Route('foo');
			self::assertNull($route->generateUrl(array('controller' => 'foo')));
		}

		public function testShouldMatchExactRouteWithoutExpectedValuesIfRouteValuesMatchDefaults() {
			$route = new Route('foo', array('controller' => 'foo'));
			self::assertEquals('/foo', $route->generateUrl(array('controller' => 'foo')));
		}

		public function testShouldNotMatchIfConstraintsFail() {
			$route = new Route('{foo}', array('foo' => 'foo'), array('foo' => 'foo|bar'));
			self::assertNull($route->generateUrl(array('foo' => 'baz')));
			self::assertNull($route->generateUrl(array('foo' => '123')));
			self::assertNull($route->generateUrl(array('foo' => 'foofoo')));
		}

		public function testShouldMatchIfConstraintsPass() {
			$route = new Route('{foo}', array(), array('foo' => 'foo|bar'));
			self::assertEquals('/foo', $route->generateUrl(array('foo' => 'foo')));
			self::assertEquals('/bar', $route->generateUrl(array('foo' => 'bar')));
		}

		public function testShouldNotMatchIfRequiredValueIsNotGiven() {
			$route = new Route('{foo}', array(), array());
			self::assertNull($route->generateUrl(array()));
		}

		public function testShouldMatchEmptyRoute() {
			$route = new Route('');
			self::assertEquals('/', $route->generateUrl(array()));
		}

		public function testShouldMatchRoute() {
			$route = new Route('{foo}');
			$match = $route->match('/bar');
			self::assertNotNull($match);
			self::assertInstanceOf('\Facilius\RouteMatch', $match);
			self::assertEquals('bar', $match['foo']);
		}

		public function testShouldNotMatchRoute() {
			$route = new Route('{foo}/{bar}');
			$match = $route->match('/bar');
			self::assertNull($match);
		}

	}

?>