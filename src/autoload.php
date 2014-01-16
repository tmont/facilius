<?php

	spl_autoload_register(function($class) {
		$prefix = 'Tmont\\Facilius\\';
		$baseDir = __DIR__ . '/';

		$len = strlen($prefix);
		if (strncmp($prefix, $class, $len) !== 0) {
			return;
		}

		// get the relative class name
		$relativeClass = substr($class, $len);

		// replace the namespace prefix with the base directory, replace namespace
		// separators with directory separators in the relative class name, append
		// with .php
		$file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

		// if the file exists, require it
		if (is_file($file)) {
			require_once $file;
		}
	});
	
?>