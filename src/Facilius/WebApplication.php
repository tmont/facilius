<?php

	namespace Facilius;

	use Exception, LogicException;

	abstract class WebApplication {
		/**
		 * @var \Facilius\Route[]
		 */
		private $routes = array();

		/**
		 * @var \ModelBinderRegistry\ModelBinderRegistry
		 */
		private $binders;

		/**
		 * @var Response
		 */
		private $response;

		/**
		 * @var bool
		 */
		private $debugEnabled = false;

		public function __construct() {
			$this->binders = new ModelBinderRegistry();
			$this->response = new Response();
		}

		/**
		 * @return \Facilius\ModelBinderRegistry
		 */
		protected final function getBinders() {
			return $this->binders;
		}

		/**
		 * @return \Facilius\Response
		 */
		protected final function getResponse() {
			return $this->response;
		}

		protected final function registerRoute($pattern, array $defaults = array(), $routeName = null) {
			$this->routes[] = new Route($pattern, $defaults, $routeName);
			return $this;
		}

		protected function onError(Exception $e) {
			$errorMessage = '<p>An error occurred during execution of the application.</p>';
			if ($this->debugEnabled) {
				$message = '<strong>' . get_class($e) . '</strong>: ' . htmlentities($e->getMessage(), ENT_QUOTES, 'UTF8');
				$stackTrace = htmlentities($e->getTraceAsString(), ENT_QUOTES, 'UTF8');
				$errorMessage = <<<ERROR
		<p>$message</p>
		<pre>$stackTrace</pre>
ERROR;
			}

			$html = <<<HTML
<!doctype html>
<html>
	<head>
		<title>An error occurred</title>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
	</head>

	<body>
		<h1>An error occurred</h1>
$errorMessage
	</body>
</html>
HTML;

			$this->response
				->clear()
				->setStatus(500)
				->write($html)
				->flush();
		}
		
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

			$routeMatch = $this->findRoute($path);

			if (!$routeMatch) {
				throw new NoMatchedRouteException($path);
			}

			$controllerName = trim($routeMatch['controller']);
			$action = trim($routeMatch['action']);
			self::verifyControllerAndAction($routeMatch->getRoute()->getName(), $path, $controllerName, $action);

			$controller = $this->createController($controllerName);
			$result = $controller->execute(new ActionExecutionContext($request, $routeMatch, $this->binders, $action));
			if (!($result instanceof ActionResult)) {
				throw new LogicException('The action did not return an instance of \Facilius\ActionResult');
			}

			$result->execute(new ActionResultContext($action, $request, $this->response, $routeMatch));
			$this->response->flush();
		}

		private function findRoute($path) {
			foreach ($this->routes as $route) {
				$routeMatch = $route->match($path);
				if ($routeMatch) {
					return $routeMatch;
				}
			}

			return null;
		}

		private static function verifyControllerAndAction($routeName, $path, $controllerName, $action) {
			if (!$controllerName) {
				throw new InvalidRouteMatchException("No controller specified in route \"$routeName\" for path \"$path\"");
			}

			if (!$action) {
				throw new InvalidRouteMatchException("No action specified in route \"$routeName\" for path \"$path\"");
			}
		}

		/**
		 * @param string $name
		 * @return Controller
		 */
		protected abstract function createController($name);

	}

?>