<?php

	namespace Tmont\Facilius;

	class RedirectResult implements ActionResult {
		private $redirectUrl;
		private $redirectStatusCode;

		public function __construct($redirectUrl, $statusCode = 302) {
			$this->redirectUrl = $redirectUrl;
			$this->redirectStatusCode = $statusCode;
		}

		public function execute(ActionResultContext $context) {
			$context->response
				->setStatus($this->redirectStatusCode)
				->setHeader('Location', $this->redirectUrl);
		}
	}

?>