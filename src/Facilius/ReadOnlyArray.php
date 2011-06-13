<?php

	namespace Facilius;

	use BadMethodCallException, ArrayAccess, Countable;

	/**
	 * A readonly wrapper around an array
	 */
	class ReadOnlyArray implements ArrayAccess, Countable {
		private $data;
		private $count;

		public function __construct(array $data) {
			$this->data = $data;
			$this->count = count($this->data);
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

		public function count() {
			return $this->count;
		}
	}
	
?>