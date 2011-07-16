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
		 * @var View|null
		 */
		private $child;

		public function __construct($path, View $child = null) {
			$this->path = $path;
			$this->sections = array();
			$this->child = $child;
		}

		public function render(RenderingContext $context) {
			if (!is_file($this->path) || !is_readable($this->path)) {
				throw new RuntimeException("The path \"$this->path\" is not a file or is not readable");
			}

			$html = new HtmlHelper($context);
			$model = $context->model;

			ob_start();
			require $this->path;

			$extraBuffer = ob_get_clean();
			if ($this->currentSection) {
				$this->sections[$this->currentSection] = $extraBuffer;
			} else {
				echo $extraBuffer;
			}

			$this->currentSection = null;

			if ($this->parent) {
				$this->parent->render($context);
			}
		}

		private function renderSection($name, $trimWhitespace = false) {
			if (!isset($this->child, $this->child->sections[$name])) {
				return;
			}

			echo $trimWhitespace ? trim($this->child->sections[$name]) : $this->child->sections[$name];
		}

		private function section($name) {
			$buffer = ob_get_contents();
			if ($this->currentSection) {
				$this->sections[$this->currentSection] = $buffer;
			} else if (trim($buffer)) {
				throw new RuntimeException('Cannot have non-whitespace data outside of a section');
			}

			ob_clean();

			$this->currentSection = $name;
		}

		public function setParent($path) {
			$this->parent = new self($path, $this);
		}
	}
?>