<?php

	namespace Facilius;

	interface BeforeActionFilter {
		function execute(ActionExecutionContext $context);
	}

?>