<?php

	namespace Facilius\Tests;

	use Facilius\LowercaseHyphenUrlTransformer;
	use PHPUnit_Framework_TestCase;

	class UrlTransformerTests extends PHPUnit_Framework_TestCase {

		private $transformer;

		public function __construct() {
			$this->transformer = new LowercaseHyphenUrlTransformer();
		}

		public function testReplaceCapitalLettersWithHyphensAndLowercaseLetters() {
			self::assertEquals('foo-bar', $this->transformer->transform('fooBar'));
		}

		public function testLowercaseFirstLetterOfWord() {
			self::assertEquals('foo', $this->transformer->transform('Foo'));
		}

		public function testCapitalizeFirstLetter() {
			self::assertEquals('Foo', $this->transformer->untransform('foo'));
		}

		public function testReplaceHyphensWithCapitalLetters() {
			self::assertEquals('FooBar', $this->transformer->untransform('foo-bar'));
		}

		public function testIgnoreConsecutiveHyphens() {
			self::assertEquals('FooBar', $this->transformer->untransform('foo--bar'));
		}

	}

?>