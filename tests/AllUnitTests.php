<?php

	namespace Facilius\Tests;

	use PHP_CodeCoverage_Filter;
	use RecursiveIteratorIterator, RecursiveDirectoryIterator;

	$projectName = getenv('PROJECT_NAMESPACE');
	if (empty($projectName)) {
		fwrite(STDERR, 'Environment variable "PROJECT_NAMESPACE" must be set to a non-empty value');
		exit(1);
	}

	$baseDir  = dirname(__DIR__);
	$testsDir = $baseDir . DIRECTORY_SEPARATOR . 'tests';
	$srcDir   = $baseDir . DIRECTORY_SEPARATOR . 'src';
	$filter = PHP_CodeCoverage_Filter::getInstance();
	
	$filter->addDirectoryToWhiteList($srcDir);
	$filter->addFileToBlacklist(__FILE__, 'PHPUNIT');

	//get all the test files
	$GLOBALS['test_classes'] = array();
	foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($testsDir)) as $file) {
		if (
			$file->isFile() && 
			strpos($file->getPathName(), $testsDir . DIRECTORY_SEPARATOR . $projectName) === 0 &&
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
	
	unset($filter, $projectName, $testsDir, $srcDir, $baseDir, $file, $testClass);

	/**
	 * Test suite that runs all unit tests
	 */
	class AllUnitTests {
		
		/**
		 * Creates a test suite
		 *
		 * @return PHPUnit_Framework_TestSuite
		 */
		public static function suite() {
			$suite = new \PHPUnit_Framework_TestSuite('All Unit tests');
			
			foreach ($GLOBALS['test_classes'] as $class) {
				//echo $class ."\n";
				$suite->addTestSuite($class);
			}
			
			return $suite;
		}
		
	}

?>
