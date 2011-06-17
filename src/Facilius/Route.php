<?php

	namespace Facilius;

	use InvalidArgumentException;

	class Route {
		private $pattern;
		private $defaults;
		private $name;
		private $constraints;

		public function __construct($pattern, array $defaults = array(), array $constraints = array(), $routeName = null) {
			$this->pattern = $pattern;
			$this->defaults = $defaults;
			$this->name = $routeName;
			$this->constraints = $constraints;
		}

		public function match($path) {
			$data = RouteParser::parse($this->pattern, trim($path, '/'), $this->defaults, $this->constraints);
			if (!$data) {
				return null;
			}

			return new RouteMatch($this, $data);
		}

		public function getName() {
			return $this->name;
		}

		public function generateUrl(array $routeValues) {
			$routeValues = array_merge($this->defaults, $routeValues);
			$url = null;

            $groupRegex = '@(?=\{)\{(.*?)\}(?!\})@';
            preg_match_all($groupRegex, $this->pattern, $expectedValues);
            $expectedValues = @$expectedValues[1] ?: array();

            if (empty($expectedValues)) {
	            if (empty($routeValues) || $this->defaults == $routeValues) {
	                $url = $this->pattern;
                }
            } else if ($this->matchesRouteValues($expectedValues, $routeValues)) {
                //if the value is the same as the default value, then we don't need to append it to the URL,
                //but only if everything is in order from left to right: if a value is different further to the
                //left, then we have to generate the entire URL
                $url = '';
                foreach (array_reverse($expectedValues) as $expectedValue) {
                    if (array_key_exists($expectedValue, $routeValues) && array_key_exists($expectedValue, $this->defaults) && $routeValues[$expectedValue] === $this->defaults[$expectedValue]) {
                        $routeValues[$expectedValue] = '';
                    } else {
                        break;
                    }
                }

                if (!empty($routeValues)) {
                    $valuesForUrl = array();
                    foreach ($expectedValues as $expectedValue) {
                        $valuesForUrl[$expectedValue] = @$routeValues[$expectedValue] ?: '';
                    }

                    $url = '/' . $this->pattern;
                    foreach ($valuesForUrl as $value => $valueForUrl) {
                        $url = preg_replace('/(?=\{)\{' . $value . '\}(?!\})/', $valueForUrl, $url);
                    }

                    $url = rtrim($url, '/');
                    foreach ($expectedValues as $expectedValue) {
                        unset($routeValues[$expectedValue]);
                    }

                    $url .= $this->generateQueryString($routeValues);
                }
            }

            return $url;
		}

		protected function matchesRouteValues(array $expectedValues, array $routeValues) {
            foreach ($expectedValues as $expectedValue) {
                if (!array_key_exists($expectedValue, $routeValues)) {
                    //an unused expected value
	                echo $expectedValue;
	                return false;
                } else if (isset($this->constraints[$expectedValue]) && !preg_match('@' . $this->constraints[$expectedValue] . '@', $routeValues[$expectedValue])) {
	                //constraint doesn't match
	                return false;
                }
            }

            //go through route values, and verify that they don't override defaults, but only for values that are not expected!
            //the point is that the expected values are the dynamic part of the route, but if they aren't given, then they
            //shouldn't be overwritten by the given route values
            foreach ($routeValues as $key => $routeValue) {
                if (in_array($key, $expectedValues)) {
                    continue;
                }

                if (isset($this->defaults[$key]) && $routeValue !== $this->defaults[$key]) {
                    return false;
                }
            }

            return true;
        }

		private function generateQueryString(array $values) {
            $query = array();
            foreach ($values as $key => $value) {
                //if it's not a default value, then it gets appended to the query string
                if (!isset($this->defaults[$key])) {
                    $query[$key] = $value;
                }
            }

            return !empty($query) ? '?' . http_build_query($query) : '';
        }

	}
	
?>