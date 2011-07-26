<?php

	namespace Facilius;

	use RuntimeException;

	class DefaultActionFilterFactory implements ActionFilterFactory {

		private $namespaces;

		public function __construct(array $namespaces = array()) {
			$this->namespaces = $namespaces;
			array_unshift($this->namespaces, '');
		}

		public function create($filterName) {
			foreach ($this->namespaces as $namespace) {
				$className = "$namespace\\$filterName";
				if (class_exists($className)) {
					return $this->instantiateFilter($className);
				}
			}

			throw new RuntimeException("Unable to create action filter \"$filterName\"");
		}

		protected function instantiateFilter($filterName) {
			return new $filterName();
		}
	}

?>