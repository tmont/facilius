<?php
	
	namespace Tmont\Facilius\Tests;
	
	use PHPUnit_Framework_TestCase;
	use Tmont\Facilius\Request;

	class RequestTest extends PHPUnit_Framework_TestCase {

		public function testRequestParsing() {
			$get = array('foo' => 'bar');
			$post = array('post' => 'value');
			$server = array(
				'REMOTE_ADDR' => '127.0.0.1',
				'SERVER_PORT' => '8080',
				'HTTP_HOST' => 'example.com',
				'SERVER_PROTOCOL' => 'HTTP/1.1',
				'REQUEST_URI' => '/foo.php?foo=bar',
				'QUERY_STRING' => 'foo=bar'
			);
			$cookie = array('cookie' => 'delicious');
			$files = array('files' => 'meh');

			$request = new Request($get, $post, $cookie, $files, $server);

			self::assertEquals('bar', $request->queryString['foo']);
			self::assertEquals('value', $request->post['post']);
			self::assertEquals('delicious', $request->cookie['cookie']);
			self::assertEquals('meh', $request->files['files']);

			self::assertEquals('127.0.0.1', $request->ipAddress);
			self::assertEquals('http', $request->protocol);
			self::assertEquals('example.com', $request->host);
			self::assertEquals('8080', $request->port);
			self::assertEquals('foo=bar', $request->rawQueryString);
			self::assertEquals('/foo.php', $request->path);
			self::assertEquals('http://example.com:8080/foo.php?foo=bar', $request->url);
		}

		public function testShouldNotIncludeDefaultPortInUrl() {
			$server = array(
				'HTTP_HOST' => 'example.com',
				'SERVER_PROTOCOL' => 'HTTP/1.1',
				'REQUEST_URI' => '/foo.php'
			);
			$request = new Request(array(), array(), array(), array(), $server);

			self::assertEquals('http://example.com/foo.php', $request->url);
		}

		public function testProtocolDefaultsToHttp() {
			$server = array(
				'HTTP_HOST' => 'example.com',
				'REQUEST_URI' => '/foo.php'
			);
			$request = new Request(array(), array(), array(), array(), $server);

			self::assertEquals('http://example.com/foo.php', $request->url);
		}

	}

?>