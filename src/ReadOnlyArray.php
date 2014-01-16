<?php

	namespace Tmont\Facilius;

	use BadMethodCallException;

	/**
	 * A readonly wrapper around an array
	 */
	class ReadOnlyArray extends ValidatableArray {
		public function __construct(array $data) {
			parent::__construct(function() { return true; }, $data);
		}

		public function offsetSet($offset, $value) {
			throw new BadMethodCallException('This array is readonly');
		}

		public function offsetUnset($offset) {
			throw new BadMethodCallException('This array is readonly');
		}
	}
	
?>