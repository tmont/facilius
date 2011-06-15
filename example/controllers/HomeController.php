<?php

	namespace Facilius\Example\Controllers;

	use Facilius\Controller;

	class HomeController extends Controller {

		public function index() {
			return $this->view('index');
		}

	}

?>