<?php

	namespace Tmont\Facilius;

	interface BeforeActionFilter {
		function execute(ActionExecutionContext $context);
	}

?>