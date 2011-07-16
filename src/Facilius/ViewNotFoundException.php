<?php

	namespace Facilius;

	use Exception;

	class ViewNotFoundException extends Exception {
		private $viewName;
		private $attemptedLocations;

		public function __construct($viewName, array $attemptedLocations) {
			$this->viewName = $viewName;
			$this->attemptedLocations = $attemptedLocations;

			parent::__construct(sprintf(
				'Unable to locate the view "%s". Searched the following locations: %s',
				$viewName,
				array_reduce($this->attemptedLocations, function($current, $next) { return "$current, $next"; })
			));
		}
	}

?>