<?php

	namespace Facilius;

	use InvalidArgumentException, RuntimeException;

	class RouteParser {

        //@codeCoverageIgnoreStart
        private function __construct() {}
        //@codeCoverageIgnoreEnd

        public static function parse($routeUrl, $requestPath, array $defaults, array $constraints) {
            if (!is_string($routeUrl)) {
                throw new InvalidArgumentException('routeUrl must be a string');
            }

            if (!empty($routeUrl) && (in_array($routeUrl[0], array('/', '~')) || strpos($routeUrl, '?') !== false)) {
                throw new InvalidArgumentException('The route url cannot start with "/" or "~" and cannot contain "?".');
            }

            $regex = self::routeUrlToRegex($routeUrl, $defaults, $constraints);

            $result = preg_match($regex, $requestPath, $values);
            if ($result === false) {
                throw new RuntimeException(sprintf('The URL-to-regex conversion returned an invalid regex for route URL "%s": "%s"', $routeUrl, $regex));
            }
            if (!$result) {
                return null;
            }

            foreach ($values as $key => $value) {
                if (ctype_digit((string)$key)) {
                    //we only care about the associative keys
                    unset($values[$key]);
                }
            }

            return $values + $defaults;
        }

        private static function routeUrlToRegex($url, array $defaults, array $constraints) {
            $regex = '';
            $inGroup = false;
            $slash = false;
            $group = '';
            $catchAll = false;
            for ($i = 0, $len = strlen($url); $i < $len; $i++) {
                $char = $url[$i];
                if ($catchAll && !$inGroup) {
                    throw new RuntimeException('Invalid route: catch all must be last sequence in the url');
                }

                switch ($char) {
                    case '{':
                        if (isset($url[$i + 1]) && $url[$i + 1] === '{') {
                            //output a literal "{"
                            $regex .= '\{';
                            $i++;
                        } else if ($inGroup) {
                            throw new RuntimeException('Invalid route: cannot have a curly bracket in a group name');
                        } else {
                            $inGroup = true;
                        }
                        break;
                    case '}':
                        if (!$inGroup || (isset($url[$i + 1]) && $url[$i + 1] === '}')) {
                            //output a literal "}"
                            $regex .= '\}';
                            $i++;
                        } else if ($inGroup) {
                            $groupRegex = "(?<$group>";
                            $groupRegex .= array_key_exists($group, $constraints) ? $constraints[$group] : ($catchAll ? '.*' : '.+?');
                            $groupRegex .= ')';

                            if (array_key_exists($group, $defaults)) {
                                if ($slash) {
                                    $groupRegex = "(?:/$groupRegex)";
                                }

                                $groupRegex .= '?';
                            } else if ($slash) {
                                $regex .= '/';
                            }

                            $regex .= $groupRegex;
                            $inGroup = false;
                            $slash = false;
                            $group = '';
                        }
                        break;
                    case '*':
                        if ($inGroup && empty($group) && !$catchAll) {
                            $catchAll = true;
                        } else if ($inGroup) {
                            throw new RuntimeException('Invalid route: cannot have an asterisk in a group name');
                        } else {
                            $regex .= '\*';
                        }
                        break;
                    case '/':
                        if (!$inGroup && isset($url[$i + 1]) && $url[$i + 1] === '{' && (!isset($url[$i + 2]) || $url[$i + 2] !== '{')) {
                            //a slash followed by a group; if the group has a default value, then the slash is optional
                            $slash = true;
                            $inGroup = true;
                            $i++;
                        } else if (!$catchAll) {
                            $regex .= '/';
                        }
                        break;
                    default:
                        if ($inGroup) {
                            $group .= preg_quote($char, '@');
                        } else {
                            $regex .= preg_quote($char, '@');
                        }
                        break;
                }
            }

            if ($inGroup) {
                throw new RuntimeException('Invalid route: a grouping was not closed');
            }

            return "@^$regex$@";
        }

    }

?>