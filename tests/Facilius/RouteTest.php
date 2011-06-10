<?php

	namespace Facilius\Tests;

	use Facilius\Route;

	class RouteTest extends \PHPUnit_Framework_TestCase {

		public function testRouteWithNoGroupsShouldMatchButBeEmpty() {
			$route = new Route('.*');
			$match = $route->match('/foo');

			self::assertNotNull($match);
			self::assertEmpty($match);
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

	}

?>