<?php

	namespace Facilius\Tests;

	use Facilius\Route;

	class RouteTest extends \PHPUnit_Framework_TestCase {
		public function testRouteWithNoGroupsShouldMatchButBeEmpty() {
			$route = new Route('.*');
			$match = $route->match('/foo');

			self::assertNotNull($match);
			self::assertEquals(0, count($match));
		}

		public function testRouteWithGroupsOnlyReturnsGroups() {
			$route = new Route('.*?([a-z]+)(.*)');
			$match = $route->match('/foo78');

			self::assertEquals(2, count($match));
			self::assertEquals($match[0], 'foo');
			self::assertEquals($match[1], '78');
		}

		public function testRouteShouldNotMatch() {
			$route = new Route('[a-z]+');
			$match = $route->match('1234');

			self::assertNull($match);
		}

		public function testShouldEscapeForwardSlashes() {
			$route = new Route('/foo/');
			$match = $route->match('/foo/');

			self::assertNotNull($match);
		}

		public function testShouldUseDefaultsWithNumericKeys() {
			$route = new Route('/(.+)(?:/(\d))?', array('foo', 3));
			$match = $route->match('/bar');

			self::assertNotNull($match);
			self::assertEquals('bar', $match[0]);
			self::assertEquals(3, $match[1]);
		}

		public function testShouldUseDefaultsWithNamedKeys() {
			$route = new Route('/(?<controller>.+)(?:/(?<id>\d))?', array('controller' => 'foo', 'id' => 3));
			$match = $route->match('/bar');

			self::assertNotNull($match);
			self::assertEquals('bar', $match['controller']);
			self::assertEquals(3, $match['id']);
		}
	}

?>