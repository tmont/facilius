<?php

	namespace Facilius;

	class DefaultModelBinder implements ModelBinder {

		public function bindModel(BindingContext $context) {
			//if it's a simple type, then we match names

			//if it's an array, then we wrap it in a collection and handle in a specific fashion

			//if it's a complex type, then we need to match property names, and recursively call bind model


		}
	}
	
?>