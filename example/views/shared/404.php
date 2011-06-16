<?php $this->setParent(__DIR__ . '/../shared/layout.php'); ?>

<?php $this->section('title'); ?>
	404: Not Found

<?php $this->section('body'); ?>
	<p>
		The page you were looking for was not found. Sorry.
	</p>

<?php if ($model->debugEnabled) { ?>
	<p>
		<strong><?php echo htmlentities(get_class($model->exception), ENT_QUOTES, 'UTF-8'); ?></strong>:
		<?php echo htmlentities($model->exception->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
	</p>

	<h2>Stack Trace</h2>
	<pre><?php echo htmlentities($model->exception->getTraceAsString(), ENT_QUOTES, 'UTF-8'); ?></pre>
<?php } ?>