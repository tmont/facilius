<?php

	namespace Facilius;

	interface ModelBinder {
		/**
		 * @return object
		 */
		function bindModel(BindingContext $context);
	}
	
?>