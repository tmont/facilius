<?php

	namespace Facilius;

	interface AfterActionFilter {
		function execute(ActionExecutionContext $context);
	}

?>