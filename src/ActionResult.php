<?php

	namespace Facilius;

	interface ActionResult {
		function execute(ActionResultContext $context);
	}

?>