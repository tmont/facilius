<?php

	namespace Facilius;

	class Router {

		public function __construct() {

		}

	}

	class Route {

		private $pattern;
		private $routeData;

		public function __construct($pattern, array $routeData) {
			$this->pattern = $pattern;
			$this->routeData = $routeData;
		}

		public function match($url) {
			$regex = '/' . preg_quote($this->pattern, '/') . '/';

			if (!preg_match($regex, $url, $matches)) {
				return null;
			}

			return $matches;
		}

	}

?>