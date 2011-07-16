<?php

	namespace Facilius;

	interface ViewLocator {
		/**
		 * @param string $name
		 * @param string $controller
		 * @return string The absolute path to the view file
		 */
		function locate($name, $controller);
	}
	
?>