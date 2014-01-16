<?php

	namespace Tmont\Facilius;

	use Exception;

	class ControllerConstructionException extends Exception {
		public function __construct($message) {
			parent::__construct($message);
		}
	}

?>