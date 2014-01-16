<?php

	namespace Tmont\Facilius;

	interface ActionFilterFactory {
		/**
		 * @param string $filterName
		 * @return object
		 */
		function create($filterName);
	}

?>