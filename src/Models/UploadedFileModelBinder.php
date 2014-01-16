<?php

	namespace Facilius\Models;

	use Facilius\ModelBinder;
	use Facilius\BindingContext;

	class UploadedFileModelBinder implements ModelBinder {

		/**
		 * @todo Don't use superglobals
		 * @return object
		 */
		public function bindModel(BindingContext $context) {
			return new UploadedFile(current($_FILES));
		}
	}

?>