<?php

	namespace Facilius;

	use BadMethodCallException, ArrayAccess;

	/**
	 * A readonly wrapper around an array
	 */
	class ReadOnlyArray implements ArrayAccess {
		private $data;

		public function __construct(array $data) {
			$this->data = $data;
		}

		public function offsetExists($offset) {
			return array_key_exists($offset, $this->data);
		}

		public function offsetGet($offset) {
			return @$this->data[$offset];
		}

		public function offsetSet($offset, $value) {
			throw new BadMethodCallException('This array is readonly');
		}

		public function offsetUnset($offset) {
			throw new BadMethodCallException('This array is readonly');
		}
	}
	
?>