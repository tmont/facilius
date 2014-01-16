<?php

	namespace Facilius;

	use Exception;

	class InvalidRouteMatchException extends Exception {
		public function __construct($message) {
			parent::__construct($message);
		}
	}

?>