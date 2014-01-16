<?php

	namespace Facilius\Tests;

    use Facilius\RouteParser;
    use PHPUnit_Framework_TestCase;

    class RouteParserTests extends PHPUnit_Framework_TestCase {

        public function testParseWithStaticUrl() {
            $url = 'foo/bar';
            self::assertNull(RouteParser::parse($url, 'foo', array(), array()));
            self::assertEquals(array(), RouteParser::parse($url, 'foo/bar', array(), array()));
        }

        public function testParseWithTemplatedUrl() {
            $url = '{foo}/bar';
            self::assertNull(RouteParser::parse($url, 'foo/baz', array(), array()));
            self::assertEquals(array('foo' => 'foo'), RouteParser::parse($url, 'foo/bar', array(), array()));
        }

        public function testParseWithTemplatedUrlWithCatchAll() {
            $url = 'foo/{*bar}';
            self::assertNull(RouteParser::parse($url, 'bar', array(), array()));
            self::assertEquals(array('bar' => 'bar'), RouteParser::parse($url, 'foo/bar', array(), array()));
            self::assertEquals(array('bar' => ''), RouteParser::parse($url, 'foo/', array(), array()));
        }

        public function testParseUrlWithInvalidCatchAll() {
            $this->setExpectedException('RuntimeException', 'Invalid route: catch all must be last sequence in the url');
            RouteParser::parse('{*foo}/bar', 'foo', array(), array());
        }

        public function testParseUrlWithGroupAndDefaults() {
            $defaults = array('controller' => 'home', 'action' => 'index', 'id' => '');
            $url = '{controller}/{action}/{id}';
            self::assertEquals(array('controller' => 'home', 'action' => 'index', 'id' => ''), RouteParser::parse($url, '', $defaults, array()));
            self::assertEquals(array('controller' => 'foo', 'action' => 'index', 'id' => ''), RouteParser::parse($url, 'foo', $defaults, array()));
            self::assertEquals(array('controller' => 'foo', 'action' => 'bar', 'id' => ''), RouteParser::parse($url, 'foo/bar', $defaults, array()));
            self::assertEquals(array('controller' => 'foo', 'action' => 'bar', 'id' => '5'), RouteParser::parse($url, 'foo/bar/5', $defaults, array()));
        }

        public function testParseUrlWithConstraints() {
            $constraints = array('foo' => '\d+');
            $url = '{foo}';
            self::assertEquals(array('foo' => '5'), RouteParser::parse($url, '5', array(), $constraints));
            self::assertNull(RouteParser::parse($url, 'lol', array(), $constraints));
        }

        public function testParseUrlWithAsteriskInGroup() {
            $this->setExpectedException('RuntimeException', 'Invalid route: cannot have an asterisk in a group name');
            $url = '{fo*o}';
            RouteParser::parse($url, 'foo', array(), array());
        }

        public function testParseUrlWithAsterisk() {
            $url = '{foo}/*';
            self::assertEquals(array('foo' => 'bar'), RouteParser::parse($url, 'bar/*', array(), array()));
            self::assertNull(RouteParser::parse($url, 'foo/bar', array(), array()));
        }

        public function testParseUrlWithCurlyBrackets() {
            $url = '{{foo}}';
            self::assertEquals(array(), RouteParser::parse($url, '{foo}', array(), array()));
            self::assertNull(RouteParser::parse($url, 'foo', array(), array()));
        }

        public function testParseUrlWithCurlyBracketInGroup() {
            $this->setExpectedException('RuntimeException', 'Invalid route: cannot have a curly bracket in a group name');
            $url = '{fo{}o}';
            RouteParser::parse($url, 'foo', array(), array());
        }

        public function testParseUrlWithUnclosedGroup() {
            $this->setExpectedException('RuntimeException', 'Invalid route: a grouping was not closed');
            $url = '{foo';
            RouteParser::parse($url, 'foo', array(), array());
        }

    }

?>