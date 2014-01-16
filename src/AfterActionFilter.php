<?php

	namespace Tmont\Facilius;

	interface AfterActionFilter {
		function execute(ActionExecutionContext $context);
	}

?>