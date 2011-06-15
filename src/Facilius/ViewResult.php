<?php

	namespace Facilius;

	class ViewResult implements ActionResult {

		private $view;
		private $model;

		public function __construct(View $view, $model = null) {
			$this->view = $view;
			$this->model = $model;
		}

		public function execute(ActionResultContext $context) {
			$this->view->render($this->model);
		}

	}

?>