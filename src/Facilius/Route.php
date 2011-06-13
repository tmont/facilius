<?php

	namespace Facilius;

	class Route {
		private $pattern;
		private $defaults;
		private $name;

		public function __construct($pattern, array $defaults = array(), $routeName = null) {
			$this->pattern = $pattern;
			$this->defaults = $defaults;
			$this->name = $routeName;
		}

		public function match($url) {
			$regex = '/' . str_replace('/', '\\/', $this->pattern) . '/';

			if (!preg_match($regex, $url, $matches)) {
				return null;
			}

			$data = array_slice($matches, 1) + $this->defaults;
			return new RouteMatch($this, $data);
		}

		public function getName() {
			return $this->name;
		}

	}
	
?>