<?php

	/**
	 * Dynamically runs a bunch of tests under a directory, or a single
	 * test
	 *
	 * This script determines which tests to run based on the TEST_COMPONENT
	 * environment variable. Normally, this script is run from the shell,
	 * and the environment variable is only around as long as the shell
	 * is open.
	 */
	
	namespace Facilius\Tests;

	use PHP_CodeCoverage_Filter;
	use RecursiveIteratorIterator, RecursiveDirectoryIterator;

	$path = getenv('TEST_COMPONENT');
	$projectName = getenv('PROJECT_NAMESPACE');
	if (empty($path) || empty($projectName)) {
		fwrite(STDERR, 'Environment variables "TEST_COMPONENT" and "PROJECT_NAMESPACE" must be set to a non-empty value');
		exit(1);
	}

	$baseDir         = dirname(__DIR__);
	$testsDir        = $baseDir . DIRECTORY_SEPARATOR . 'tests';
	$GLOBALS['path'] = $testsDir . DIRECTORY_SEPARATOR . $projectName . DIRECTORY_SEPARATOR . $path;

	if (!is_dir($GLOBALS['path'])) {
		$tempPath = $GLOBALS['path'] . 'Test.php';
		$tempPath2 = $GLOBALS['path'] . 'Tests.php';
		if (is_file($tempPath)) {
			$GLOBALS['path'] = $tempPath;
			unset($tempPath, $tempPath2);
		} else if (is_file($tempPath2)) {
			$GLOBALS['path'] = $tempPath2;
			unset($tempPath, $tempPath2);
		} else {
			fwrite(STDERR, $GLOBALS['path'] . ' is neither a directory nor a file');
			exit(1);
		}
	}

	$filter = PHP_CodeCoverage_Filter::getInstance();

	$filter->addFileToBlacklist(__FILE__, 'PHPUNIT');

	//iterates over all the files in the directory, and require_once's them
	//or, if it's just a single file, it require_once's that single file
	$GLOBALS['test_classes'] = array();
	if (is_dir($GLOBALS['path'])) {
		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($GLOBALS['path'])) as $file) {
			if (
				$file->isFile() &&
				strpos($file->getPathName(), DIRECTORY_SEPARATOR . '.') === false &&
				preg_match('/Tests?\.php$/', $file->getFileName())
			) {
				$testClass = ltrim(str_replace($testsDir, '', $file->getPathName()), DIRECTORY_SEPARATOR . '/');
				$testClass = str_replace(DIRECTORY_SEPARATOR, '\\', $testClass);
				$testClass = str_replace("$projectName\\", "$projectName\\Tests\\", $testClass);
				$testClass = substr($testClass, 0, -4);
				$GLOBALS['test_classes'][] = $testClass;
				require_once $file->getPathname();
			}
		}

		unset($file);
	} else {
		require_once $GLOBALS['path'];
		$testClass = ltrim(str_replace($testsDir, '', $GLOBALS['path']), DIRECTORY_SEPARATOR . '/');
		$testClass = str_replace("$projectName\\", "$projectName\\Tests\\", $testClass);
		$testClass = substr($testClass, 0, -4);
		$GLOBALS['test_classes'][] = $testClass;

		unset($testClass);
	}

	unset($filter, $baseDir, $testsDir, $path);

	if (empty($GLOBALS['test_classes'])) {
		fwrite(STDERR, 'No test classes found');
		exit(1);
	}

	/**
	 * Dynamically runs a bunch of tests under a directory, or a single
	 * test
	 */
	class DynamicTestSuite {

		/**
		 * Creates a test suite
		 *
		 * @return PHPUnit_Framework_TestSuite
		 */
		public static function suite() {
			$suite = new \PHPUnit_Framework_TestSuite('Tests From ' . $GLOBALS['path']);

			foreach ($GLOBALS['test_classes'] as $class) {
				$suite->addTestSuite($class);
			}

			return $suite;
		}

	}

?>