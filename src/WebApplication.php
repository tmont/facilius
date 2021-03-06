<?php

	namespace Tmont\Facilius;

	use Exception, LogicException;
	use Tmont\Facilius\Models\UploadedFileModelBinder;

	abstract class WebApplication {
		/**
		 * @var \Tmont\Facilius\Route[]
		 */
		private $routes = array();

		/**
		 * @var \Tmont\Facilius\ModelBinderRegistry
		 */
		private $binders;

		/**
		 * @var Response
		 */
		private $response;

		/**
		 * @var bool
		 */
		protected $debugEnabled = false;

		/**
		 * @var Request
		 */
		private $currentRequest;

		/**
		 * @var \Tmont\Facilius\UrlTransformer
		 */
		protected $urlTransformer;

		private $viewPath;

		public function __construct($viewPath) {
			$this->binders = new ModelBinderRegistry();
			$this->binders->addBinder('Tmont\Facilius\Models\UploadedFile', new UploadedFileModelBinder());
			$this->response = new Response();
			$this->viewPath = $viewPath;
		}

		/**
		 * @return \Tmont\Facilius\ModelBinderRegistry
		 */
		protected final function getBinders() {
			return $this->binders;
		}

		/**
		 * @return \Tmont\Facilius\Response
		 */
		protected final function getResponse() {
			return $this->response;
		}

		/**
		 * @return \Tmont\Facilius\Request
		 */
		protected final function getRequest() {
			return $this->currentRequest;
		}

		/**
		 * @return Route[]
		 */
		protected final function getRoutes() {
			return $this->routes;
		}

		/**
		 * @return string
		 */
		protected final function getViewPath() {
			return $this->viewPath;
		}

		protected final function registerRoute($pattern, array $defaults = array(), array $constraints = array(), $routeName = null) {
			$this->routes[] = new Route($pattern, $defaults, $constraints, $routeName);
			return $this;
		}

		protected function onError(Exception $e) {
			$errorMessage = '<p>An error occurred during execution of the application.</p>';
			if ($this->debugEnabled) {
				$message = '<strong>' . get_class($e) . '</strong>: ' . htmlentities($e->getMessage(), ENT_QUOTES, 'UTF-8');
				$stackTrace = htmlentities($e->getTraceAsString(), ENT_QUOTES, 'UTF-8');
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

			$statusCode = $e instanceof HttpException ? $e->getStatusCode() : 500;

			$this->response
				->clear()
				->setStatus($statusCode)
				->write($html)
				->flush();
		}
		
		protected function onStart() {}
		protected function onEnd() {}

		public function run(Request $request, array $session = array()) {
			$this->currentRequest = $request;
			
			ob_start();
			try {
				$this->onStart();
				$this->handleRequest($request, $session);
			} catch (Exception $e) {
				$this->onError($e);
			}

			try {
				$this->onEnd();
			} catch (Exception $e) {
				$this->onError($e);
			}

			$output = ob_get_clean();
			$this->response->write($output)->flush();
		}

		private function transformPath($path) {
			$segments = explode('/', $path);
			$transformer = $this->urlTransformer ?: new LowercaseHyphenUrlTransformer();
			foreach ($segments as &$segment) {
				$segment = $transformer->untransform($segment);
			}

			return implode('/', $segments);
		}

		private function handleRequest(Request $request, array $session) {
			$path = $request->path;
			$routeMatch = $this->findRoute($path);

			if (!$routeMatch) {
				throw new NoMatchedRouteException($path);
			}

			$controllerName = $this->transformPath(trim($routeMatch['controller']));
			$action = $this->transformPath(trim($routeMatch['action']));
			self::verifyControllerAndAction($routeMatch->getRoute()->getName(), $path, $controllerName, $action);

			$controller = $this->createController($controllerName);
			if (!$controller) {
				throw new ControllerConstructionException("Unable to create controller for path \"$path\"");
			}

			$result = $controller->execute(new ActionExecutionContext($request, $session, $this->routes, $routeMatch, $this->binders, $action));
			if (!($result instanceof ActionResult)) {
				throw new LogicException("The action \"$controllerName::$action\" did not return an instance of \\Facilius\\ActionResult");
			}

			$result->execute(new ActionResultContext($action, $request, $this->response, $routeMatch, $controller, $this->routes));
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