<?php

	namespace Tmont\Facilius;

	use Exception;

	class InvalidValueException extends Exception {
		public function __construct($message) {
			parent::__construct($message);
		}
	}

?>