<?php

	namespace Facilius;

	use Exception;

	class HttpException extends Exception {

		private $statusCode;

		public function __construct($statusCode, $message = '') {
			parent::__construct($message);
			$this->statusCode = $statusCode;
		}

		public final function getStatusCode() {
			return $this->statusCode;
		}

	}

?>