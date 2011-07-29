<?php

	namespace Facilius;

	use ReflectionMethod, Exception;

	abstract class Controller {

		/**
		 * @var ViewLocator
		 */
		private $viewLocator;

		/**
		 * @var \Facilius\ActionFilterFactory
		 */
		private $actionFilterFactory;

		/**
		 * @var ActionExecutionContext
		 */
		private $currentContext;

		public function __construct() {
			$this->actionFilterFactory = new DefaultActionFilterFactory();
		}

		public final function setViewLocator(ViewLocator $viewLocator) {
			$this->viewLocator = $viewLocator;
		}

		public final function getViewLocator() {
			return $this->viewLocator;
		}

		public final function setActionFilterFactory(ActionFilterFactory $actionFilterFactory) {
			$this->actionFilterFactory = $actionFilterFactory;
		}

		protected final function getContext() {
			return $this->currentContext;
		}

		/**
		 * @param ActionExecutionContext $context
		 * @return ActionResult
		 */
		public final function execute(ActionExecutionContext $context) {
			$this->currentContext = $context;
			
			if (!method_exists($this, $context->action)) {
				return $this->handleUnknownAction($context);
			}

			$method = new ReflectionMethod($this, $context->action);
			$requestMethod = ReflectionUtil::getRequestMethod($method);
			if ($requestMethod && strtolower($context->request->requestMethod) !== strtolower($requestMethod)) {
				//if @request-method annotation exists and does not match the incoming request method, then the action is not a match
				return $this->handleUnknownAction($context);
			}

			$beforeFilters = $this->getActionFilters($method, 'before');
			foreach ($beforeFilters as $filter) {
				$filter->execute($context);
			}

			$refParams = $method->getParameters();

			//create parameters for action, i.e. model binding
			if (count($refParams) > 0) {
				$params = array();
				$requestValues = array_merge($context->routeMatch->getData(), $context->request->queryString->toArray(), $context->request->post->toArray());

				foreach ($refParams as $param) {
					$type = ReflectionUtil::getParameterType($param);
					$params[$param->getPosition()] = $context
						->modelBinders
						->getBinderOrDefault($type)
						->bindModel(new BindingContext($requestValues, $context, $param->getName(), $type));
				}

				return $method->invokeArgs($this, $params);
			}

			$executedContext = new ActionExecutedContext($context, $method->invoke($this), $method);

			$afterFilters = $this->getActionFilters($method, 'after');
			foreach ($afterFilters as $filter) {
				$filter->execute($executedContext);
			}

			return $executedContext->actionResult;
		}

		/**
		 * @param \ReflectionMethod $method
		 * @param string $type
		 * @return BeforeActionFilter[]|\Facilius\AfterActionFilter[]
		 */
		private function getActionFilters(ReflectionMethod $method, $type) {
			$docCommentValues = ReflectionUtil::getDocCommentValues($method);
			$filterNames = @$docCommentValues[$type . '-filter'] ?: array();
			$filters = array();
			foreach ($filterNames as $filterName) {
				$filters[] = $this->actionFilterFactory->create($filterName);
			}

			return $filters;
		}

		protected function handleUnknownAction(ActionExecutionContext $context) {
			throw new UnknownActionException(get_class($this), $context->action);
		}

		public function getControllerName() {
			$parts = explode('\\', get_class($this));
			$controller = end($parts);
			return strtolower(substr($controller, 0, strlen($controller) - 10));
		}

		protected function view($name = null, $controller = null, $model = null) {
			if (!$this->viewLocator) {
				throw new Exception('View locator has not been set. Please reconfigure your app\'s createController() method.');
			}

			$path = $this->viewLocator->locate($name ?: $this->currentContext->action, $controller ?: $this->getControllerName());
			return new ViewResult(new View($path), $model);
		}

		protected function redirect($url, $httpStatusCode = 302) {
			return new RedirectResult($url, $httpStatusCode);
		}

		protected function redirectToAction($action, $controller = null, array $routeValues = array(), $httpStatusCode = 302) {
			$routeValues['action'] = $action;
			$routeValues['controller'] = $controller ?: $this->getControllerName();

			$url = '';
			foreach ($this->currentContext->routes as $route) {
				$url = $route->generateUrl($routeValues);
				if ($url !== null) {
					break;
				}
			}

			return $this->redirect($url ?: '', $httpStatusCode);
		}

		public function json($decodedData) {
			return new JsonResult($decodedData);
		}

		public function file($fileName, $contentType, $encoding = 'UTF-8') {
			return new ContentResult(file_get_contents($fileName), $contentType, $encoding);
		}

	}

?>