<?php

	namespace Facilius;

	use InvalidArgumentException;

	class Response {

		private $headers = array();
		private $buffer = '';
		private $statusCode = 200;

		private function sendHeaders() {
			if (!headers_sent()) {
				foreach ($this->headers as $name => $value) {
					header("$name: $value");
				}

				header("HTTP/1.1 $this->statusCode");
			}
		}

		public function setHeader($name, $value) {
			if (strpos($value, "\r\n") >= 0 || strpos($name, "\r\n") >= 0) {
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