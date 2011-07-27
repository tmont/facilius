<?php

	namespace Facilius;

	class JsonResult implements ActionResult {
		private $data;

		public function __construct($data) {
			$this->data = $data;
		}

		public function execute(ActionResultContext $context) {
			$result = new ContentResult(json_encode($this->data), 'application/json');
			return $result->execute($context);
		}
	}

?>