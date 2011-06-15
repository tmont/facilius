<?php

	namespace Facilius;

	interface ViewLocator {
		function locate($name, $controller);
	}
	
?>