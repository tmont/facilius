<?php

	namespace Tmont\Facilius;

	interface ModelBinder {
		/**
		 * @return object
		 */
		function bindModel(BindingContext $context);
	}
	
?>