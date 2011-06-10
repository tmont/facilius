<?php

	namespace Facilius;

	class Route {
		private $pattern;
		private $defaults;

		public function __construct($pattern, array $defaults = array()) {
			$this->pattern = $pattern;
			$this->defaults = $defaults;
		}

		public function match($url) {
			$regex = '/' . str_replace('/', '\\/', $this->pattern) . '/';

			if (!preg_match($regex, $url, $matches)) {
				return null;
			}

			return array_slice($matches, 1) + $this->defaults;
		}
	}

?>