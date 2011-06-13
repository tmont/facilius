<?php

	namespace Facilius;

	use Iterator, ArrayAccess, Countable, Closure;

	class StronglyTypedCollection implements Iterator, ArrayAccess, Countable {

		private $data = array();
		private $type;

		public function __construct($type) {
			$this->type = $type;
		}

		private function validate($value) {
			return $value instanceof $this->type;
		}

		public function current() {
			return current($this->data);
		}

		public function key() {
			return key($this->data);
		}

		public function next() {
			return next($this->data);
		}

		public function offsetExists($offset) {
			return array_key_exists($offset, $this->data);
		}

		public function offsetGet($offset) {
			if (!$this->offsetExists($offset)) {
				return null;
			}

			return $this->data[$offset];
		}

		public function offsetSet($offset, $value) {
			if (!$this->validate($value)) {
				throw new InvalidValueException();
			}

			$this->data[$offset] = $value;
		}

		public function offsetUnset($offset) {
			unset($this->data[$offset]);
		}

		public function rewind() {
			reset($this->data);
		}

		public function valid() {
			return $this->key() !== null;
		}

		public function count() {
			return count($this->data);
		}
	}

?>