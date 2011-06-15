<?php

	namespace Facilius;

	class DefaultViewLocator implements ViewLocator {

		private $baseDir;

		public function __construct($baseDir) {
			$this->baseDir = $baseDir;
		}

		public function locate($name, $controller) {
			$path = "$this->baseDir/views/$controller/$name.php";
			if (is_file($path)) {
				return $path;
			}

			return "$this->baseDir/views/shared/$name.php";
		}
	}
	
?>