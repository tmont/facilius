<?php

	namespace Tmont\Facilius\Example\Controllers;

	use Tmont\Facilius\Controller;

	class HomeController extends Controller {

		public function index() {
			return $this->view('index');
		}

		/**
		 * @request-method post
		 */
		public function form(ExampleModel $model) {
			return $this->view('form', null, $model);
		}

	}

	class ExampleModel {
		public $foo;
		/**
		 * @var int
		 */
		public $bar;
	}

?>