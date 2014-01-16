<?php

	namespace Facilius;

	use RuntimeException;

	/**
	 * Wrapper around an HTTP request.
	 *
	 * Use the static {@link Request::create} method to build a Request object from
	 * the incoming request.
	 *
	 * @property-read ReadOnlyArray $post The request's POST variables
	 * @property-read ReadOnlyArray $cookie The request's cookies
	 * @property-read ReadOnlyArray $files The request's uploaded file data
	 * @property-read ReadOnlyArray $server The request's server variables
	 * @property-read string $requestMethod The request method
	 * @property-read string $ipAddress The client IP address, accounting for proxies
	 * @property-read bool $proxied Whether or not the client is identifying itself as a proxy
	 * @property-read string $path The path of the request (e.g. /foo for http://domain.com/foo)
	 * @property-read string $host The host of the request (e.g. domain.com in http://domain.com/foo)
	 * @property-read string $port The port of the request
	 * @property-read string $protocol The protocol of the request (e.g. http in http://domain.com/foo)
	 * @property-read string $url The request URL
	 * @property-read ReadOnlyArray $queryString The request's query string variables
	 * @property-read string $rawQueryString The request's raw query string
	 */
	final class Request {
		private $urlData;
		private $readonlyData;

		public function __construct(array $get = array(), array $post = array(), array $cookie = array(), array $files = array(), array $server = array()) {
			list($protocol, )  = explode('/', @$server['SERVER_PROTOCOL'], 2);
			if (empty($protocol)) {
				$protocol = 'HTTP';
			}

			$requestUri = @$server['REQUEST_URI'];
			list($path, ) = explode('?', $requestUri, 2);

			$this->urlData = array(
				'port' => @$server['SERVER_PORT'] ?: 80,
				'path' => $path,
				'pathAndQuery' => $requestUri,
				'queryString' => new ReadOnlyArray($get),
				'rawQueryString' => @$server['QUERY_STRING'],
				'protocol' => strtolower($protocol),
				'host' => @$server['HTTP_HOST']
			);

			$this->urlData['url'] = sprintf(
				'%s://%s%s%s%s',
				$this->urlData['protocol'],
				$this->urlData['host'],
				$this->urlData['port'] != 80 ? ':' . $this->urlData['port'] : '',
				$this->urlData['path'],
				!empty($this->urlData['rawQueryString']) ? '?' . $this->urlData['rawQueryString'] : ''
			);

			$this->readonlyData = array(
				'post' => new ReadOnlyArray($post),
				'cookie' => new ReadOnlyArray($cookie),
				'files' => new ReadOnlyArray($files),
				'server' => new ReadOnlyArray($server),
				'referrer' => @$server['HTTP_REFERER'],
				'requestMethod' => strtoupper(@$server['REQUEST_METHOD'] ?: 'GET'),
				'ipAddress' => @$server['HTTP_X_FORWARDED_FOR'] ?: @$server['REMOTE_ADDR'],
				'proxied' => isset($server['HTTP_X_FORWARDED_FOR'])
			);
		}

		/**
		 * Builds a Request object from the incoming request using the
		 * current superglobals ($_GET, $_POST, etc.)
		 *
		 * @return \Facilius\Request
		 */
		public static function create() {
			return new self(
				$_POST,
				$_GET,
				$_COOKIE,
				$_FILES,
				$_SERVER
			);
		}

		public function __get($name) {
			switch ($name) {
				case 'get':
				case 'post':
				case 'cookie':
				case 'files':
				case 'server':
				case 'requestMethod':
				case 'ipAddress':
				case 'referrer':
					return $this->readonlyData[$name];
				case 'path':
				case 'pathAndQuery':
				case 'host':
				case 'port':
				case 'protocol':
				case 'queryString':
				case 'rawQueryString':
				case 'url':
					return @$this->urlData[$name];
				default:
					throw new RuntimeException("Unknown property Request::$name");
			}

		}

		public function __set($name, $value) {
			throw new RuntimeException("Cannot set the value of Request::$name");
		}
	}
	
?>