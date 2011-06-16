<?php

	namespace Facilius;

	use Exception;

	class ErrorModel {
		/**
		 * @var bool
		 */
		public $debugEnabled;

		/**
		 * @var Exception
		 */
		public $exception;

		public function __construct(Exception $e, $debugEnabled) {
			$this->exception = $e;
			$this->debugEnabled = $debugEnabled;
		}
	}

?>