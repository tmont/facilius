<?php

	namespace Facilius;

	use ArrayAccess;

	class RouteMatch extends ReadOnlyArray {
		/**
		 * @var \Facilius\Route
		 */
		private $route;
		private $data;

		public function __construct(Route $route, array $data) {
			parent::__construct($data);
			$this->route = $route;
			$this->data = $data;
		}

		/**
		 * @return \Facilius\Route
		 */
		public function getRoute() {
			return $this->route;
		}
	}
	
?>