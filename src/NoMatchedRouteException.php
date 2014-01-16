<?php

	namespace Facilius;

	use Exception;

	class NoMatchedRouteException extends Exception {
		public function __construct($path) {
			parent::__construct("No route matched for path \"$path\"");
		}
	}
	
?>