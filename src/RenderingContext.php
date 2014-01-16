<?php

	namespace Tmont\Facilius;

	class RenderingContext {
		/**
		 * @var View
		 */
		public $view;

		/**
		 * @var Request
		 */
		public $request;

		/**
		 * @var Controller
		 */
		public $controller;

		public $model;

		/**
		 * @var Route[]
		 */
		public $routes;

		/**
		 * @var ViewLocator|null
		 */
		public $viewLocator;

		public function __construct(View $view, Request $request, array $routes, Controller $controller = null, $model = null) {
			$this->view = $view;
			$this->request = $request;
			$this->controller = $controller;
			$this->model = $model;
			$this->routes = $routes;
			$this->viewLocator = $controller !== null ? $controller->getViewLocator() : null;
		}
	}

?>