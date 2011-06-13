<?php

	namespace Facilius;

	use Exception;

	class UnknownActionException extends Exception {
		public function __construct($controllerName, $action) {
			parent::__construct("Unknown action \"$action\" in controller \"$controllerName\"");
		}
	}

?>