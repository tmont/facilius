<?php

	namespace Facilius;

	use Iterator, ArrayAccess, Countable, Closure;

	class ValidatableArray implements Iterator, ArrayAccess, Countable {

		private $data;
		private $validator;

		public function __construct(Closure $validator, array $initialData = array()) {
			$this->data = $initialData;
			$this->validator = $validator;
		}

		private function validate($value) {
			return call_user_func($this->validator, $value);
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
				throw new InvalidValueException('Invalid value');
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

		public function toArray() {
			return $this->data;
		}
	}

?>