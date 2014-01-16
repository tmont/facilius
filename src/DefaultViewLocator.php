<?php

	namespace Facilius;

	class DefaultViewLocator implements ViewLocator {

		private $baseDir;

		public function __construct($baseDir) {
			$this->baseDir = $baseDir;
		}

		public function locate($name, $controller) {
			$paths = array(
				"$this->baseDir/views/$controller/$name.php",
				"$this->baseDir/views/shared/$name.php"
			);

			foreach ($paths as $path) {
				if (is_file($path)) {
					return $path;
				}
			}

			throw new ViewNotFoundException($name, $paths);
		}
	}
?>