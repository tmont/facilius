<?php

	namespace Facilius;

	/**
	 * A registry of model binders for an application
	 */
	final class ModelBinderRegistry {

		/**
		 * @var \Facilius\ModelBinder[]
		 */
		private $binders = array();

		/**
		 * @var \Facilius\ModelBinder
		 */
		private $defaultModelBinder;

		public function __construct() {
			$this->defaultModelBinder = new DefaultModelBinder();
		}

		/**
		 * @param string $type
		 * @param ModelBinder $binder
		 * @return ModelBinderRegistry
		 */
		public function addBinder($type, ModelBinder $binder) {
			$this->binders[$type] = $binder;
			return $this;
		}

		/**
		 * @param ModelBinder $binder
		 * @return ModelBinderRegistry
		 */
		public function setDefaultBinder(ModelBinder $binder) {
			$this->defaultModelBinder = $binder;
			return $this;
		}

		public function clear() {
			$this->binders = array();
		}

		/**
		 * @param string $type
		 * @return ModelBinder
		 */
		public function getBinderOrDefault($type) {
			return @$this->binders[$type] ?: $this->defaultModelBinder;
		}

	}

?>