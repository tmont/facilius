<?php

	namespace Facilius;

	use InvalidArgumentException, RuntimeException;

	class View {

		private $path;
		private $sections;
		private $currentSection;

		/**
		 * @var View
		 */
		private $parent;
		/**
		 * @var \Facilius\View|null
		 */
		private $child;

		public function __construct($path, View $child = null) {
			$this->path = $path;
			$this->sections = array();
			$this->child = $child;
		}

		public function render($model) {
			if (!is_file($this->path) || !is_readable($this->path)) {
				throw new RuntimeException("The path \"$this->path\" is not a file or is not readable");
			}

			ob_start();
			require $this->path;

			if ($this->currentSection) {
				$this->sections[$this->currentSection] = ob_get_clean();
			}

			$this->currentSection = null;

			if ($this->parent) {
				$this->parent->render($model);
			}
		}

		public function renderSection($name, $trimWhitespace = true) {
			if (!isset($this->child, $this->child->sections[$name])) {
				throw new InvalidArgumentException("The section \"$name\" is undefined");
			}

			echo $trimWhitespace ? trim($this->child->sections[$name]) : $this->child->sections[$name];
		}

		public function section($name) {
			if ($this->currentSection) {
				$this->sections[$this->currentSection] = ob_get_contents();
				ob_clean();
			}

			$this->currentSection = $name;
		}

		public function setParent($path) {
			$this->parent = new self($path, $this);
		}
	}

?>