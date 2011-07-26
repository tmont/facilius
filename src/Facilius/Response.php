<?php

	namespace Facilius;

	use InvalidArgumentException;

	class Response {

		private $headers = array();
		private $buffer = '';
		private $statusCode = 200;

		private static $statusCodeMap = array(
			100 => 'Continue',
			101 => 'Switching Protocols',

			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',

			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			307 => 'Temporary Redirect',

			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',

			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported'
		);

		private function sendHeaders() {
			if (!headers_sent()) {
				foreach ($this->headers as $name => $value) {
					header("$name: $value");
				}

				header("HTTP/1.1 $this->statusCode " . @self::$statusCodeMap[$this->statusCode]);
			}
		}

		public function setHeader($name, $value) {
			if (strpos($value, "\r\n") !== false || strpos($name, "\r\n") !== false) {
				throw new InvalidArgumentException('Headers cannot contain the string "\r\n"');
			}

			$this->headers[$name] = $value;
			return $this;
		}

		public function write($data) {
			$this->buffer .= $data;
			return $this;
		}

		public function flush() {
			$this->sendHeaders();
			echo $this->buffer;
			$this->clear();
		}

		public function clear() {
			$this->buffer = '';
			return $this;
		}

		public function setStatus($statusCode) {
			if (!is_int($statusCode)) {
				throw new InvalidArgumentException('Status code must be an integer');
			}

			$this->statusCode = $statusCode;
			return $this;
		}

		public function streamFile($filePath, $downloadName, $contentType) {
			if (!is_file($filePath)) {
				throw new InvalidArgumentException("The file \"$filePath\" does not exist");
			}

			$this
				->clear()
				->setHeader('Content-Disposition', 'Attachment;filename=' . $downloadName)
				->setHeader('Content-Type', $contentType)
				->setHeader('Content-Length', filesize($filePath));
		}

	}

?>