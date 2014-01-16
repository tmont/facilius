<?php

	namespace Tmont\Facilius;

	/**
	 * Provides a collection of helpful methods for generating HTML
	 */
	class HtmlHelper {

		/**
		 * @var RenderingContext
		 */
		private $context;

		public function __construct(RenderingContext $context) {
			$this->context = $context;
		}

		/**
		 * @param string $action
		 * @param string $controller
		 * @param array $routeValues
		 * @return string
		 */
		public function actionLink($text, $action, $controller = null, array $routeValues = array()) {
			return sprintf('<a href="%s">%s</a>', $this->actionUrl($action, $controller, $routeValues), $this->encode($text));
		}

		public function actionUrl($action, $controller = null, array $routeValues = array()) {
			$routeValues['action'] = $action;
			$routeValues['controller'] = $controller ?: $this->context->controller->getControllerName();

			return Route::getUrl($this->context->routes, $routeValues);
		}

		public function renderPartial($name, $controller = null, $model = null) {
			$path = $this->context->viewLocator->locate($name, $controller ?: $this->context->controller->getControllerName());
			$view = new View($path);
			$view->render(new RenderingContext($view, $this->context->request, $this->context->routes, $this->context->controller, $model));
		}

		public function encode($text) {
			return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
		}

	}

?>