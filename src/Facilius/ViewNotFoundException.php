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
				'Unable to locate the view "%s". The following locations were searched: %s',
				$viewName,
				implode(', ', $this->attemptedLocations)
			));
		}
	}

?>