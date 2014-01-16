<?php

	namespace Facilius;

	interface UrlTransformer {
		/**
		 * @param string $url
		 * @return string
		 */
		function transform($url);
		/**
		 * @param string $url
		 * @return string
		 */
		function untransform($url);
	}

?>