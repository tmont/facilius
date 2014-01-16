<?php

	namespace Tmont\Facilius;

	interface ActionResult {
		function execute(ActionResultContext $context);
	}

?>