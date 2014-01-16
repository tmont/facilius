<?php

	namespace Tmont\Facilius;

	class ContentResult implements ActionResult {

		private $data;
		private $contentType;
		private $encoding;

		public function __construct($data, $contentType = 'text/plain', $encoding = 'UTF-8') {
			$this->data = $data;
			$this->contentType = $contentType;
			$this->encoding = $encoding;
		}

		public function getData() {
			return $this->data;
		}

		public function getContentType() {
			return $this->contentType;
		}

		public function getEncoding() {
			return $this->encoding;
		}

		public function execute(ActionResultContext $context) {
			$context->response
				->setHeader('Content-Type', $this->contentType)
				->setHeader('Content-Encoding', $this->encoding)
				->write($this->data);
		}
	}

?>