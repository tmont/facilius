<?php

	namespace Facilius;

	class LowercaseHyphenUrlTransformer implements UrlTransformer {
		private static $transformedCache = array();
		private static $untransformedCache = array();

		/**
		 * @param string $url
		 * @return string
		 */
		public function transform($url) {
			if (strlen($url) === 0) {
				return $url;
			}

			if (!isset($transformedCache[$url])) {
				$newUrl = '';
				foreach (preg_split('/([A-Z])/', $url, null, PREG_SPLIT_DELIM_CAPTURE) as $i => $segment) {
					if (empty($segment)) {
						continue;
					}

					if ($i % 2 === 1) {
						$newUrl .= '-';
					}

					$newUrl .= strtolower($segment);
				}

				self::$transformedCache[$url] = ltrim($newUrl, '-');
			}

			return self::$transformedCache[$url];
		}

		/**
		 * @param string $url
		 * @return string
		 */
		public function untransform($url) {
			if (strlen($url) === 0) {
				return $url;
			}

			if (!isset($untransformedCache[$url])) {
				$newUrl = '';
				foreach (explode('-', $url) as $segment) {
					if (empty($segment)) {
						continue;
					}

					$newUrl .= ucfirst($segment);
				}

				self::$untransformedCache[$url] = $newUrl;
			}

			return self::$untransformedCache[$url];
		}
	}

?>