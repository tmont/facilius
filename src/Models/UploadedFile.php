<?php

	namespace Tmont\Facilius\Models;

	use RuntimeException;

	class UploadedFile {
		public $clientName;
		public $baseName;
		public $location;
		public $mimeType;
		/**
		 * @var int|null
		 */
		public $size;
		public $error;

		public function __construct(array $fileData = array()) {
			$this->clientName = @$fileData['name'];
			$this->location = @$fileData['tmp_name'];
			$this->mimeType = @$fileData['type'];
			$this->size = @$fileData['size'];
			$this->error = @$fileData['error'];
			if ($this->clientName) {
				$this->baseName = basename($this->clientName);
			}
		}

		public final function isValid() {
			return !$this->error;
		}

		public function moveTo($destinationDir, $overwrite = false) {
			if (!is_dir($destinationDir)) {
				if (!mkdir($destinationDir, 0755, true)) {
					throw new RuntimeException(sprintf('Unable to create directory "%s"', $destinationDir));
				}
			}

			$id = '';
			do {
				$newLocation = $destinationDir . '/' . $id . $this->baseName;
				$id = uniqid() . '_';
			} while ($overwrite && is_file($newLocation));

			if (!move_uploaded_file($this->location, $newLocation)) {
				throw new RuntimeException(sprintf('Unable to move "%s" to "%s"', $this->location, $newLocation));
			}

			return basename($newLocation);
		}
	}

?>