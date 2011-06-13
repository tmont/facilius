<?php

	namespace Facilius;

	use Exception;

	abstract class WebApplication {
		/**
		 * @var \Facilius\Route[]
		 */
		private $routes = array();

		protected final function registerRoute($pattern, array $defaults = array(), $routeName = null) {
			$this->routes[] = new Route($pattern, $defaults, $routeName);
			return $this;
		}

		protected function onError(Exception $e) {}
		protected function onStart() {}
		protected function onEnd() {}

		public function run(Request $request) {
			try {
				$this->onStart();
				$this->handleRequest($request);
			} catch (Exception $e) {
				$this->onError($e);
			}

			try {
				$this->onEnd();
			} catch (Exception $e) {
				$this->onError($e);
			}
		}

		private function handleRequest(Request $request) {
			$path = $request->path;

			$routeMatch = null;

			//find matching route
			foreach ($this->routes as $route) {
				$routeMatch = $route->match($path);
			}

			if (!$routeMatch) {
				//no route found
				throw new NoMatchedRouteException($path);
			}

			$controllerName = $routeMatch['controller'];
			$action = $routeMatch['action'];

			if (!$controllerName) {
				$routeName = $routeMatch->getRoute()->getName();
				throw new InvalidRouteMatchException(
					"No controller specified in route \"$routeName\" for path \"$path\""
				);
			}

			if (!$action) {
				$routeName = $routeMatch->getRoute()->getName();
				throw new InvalidRouteMatchException(
					"No action specified in route \"$routeName\" for path \"$path\""
				);
			}

			$controller = $this->createController($controllerName);
			$result = $controller->execute($action, new ActionExecutionContext($request, $routeMatch));
			
		}

		/**
		 * @param $name
		 * @return \Facilius\Controller
		 */
		protected abstract function createController($name);

	}

?>