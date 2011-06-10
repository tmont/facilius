<?php

	namespace Facilius;

	class Route {

		private $pattern;
		private $routeData;

		public function __construct($pattern, array $routeData = array()) {
			$this->pattern = $pattern;
			$this->routeData = $routeData;
		}

		public function match($url) {
			$regex = '/' . str_replace('/', '\\/', $this->pattern) . '/';

			if (!preg_match($regex, $url, $matches)) {
				return null;
			}

			return isset($matches[1]) ? array_slice($matches, 1) : array();
		}

	}

?>