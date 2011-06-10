<?php
	
	spl_autoload_register(function($className) {
		$file = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, trim($className, '\\')) . '.php';
		if (is_file($file)) {
			require_once $file;
		}
	});
	
?>