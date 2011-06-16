<?php $this->setParent(__DIR__ . '/../shared/layout.php'); ?>

<?php $this->section('title'); ?>
	An error occurred

<?php $this->section('body'); ?>
		<h1>An error occurred</h1>

<?php if ($model->debugEnabled) { ?>
	<p>
		<strong><?php echo htmlentities(get_class($model->exception), ENT_QUOTES, 'UTF-8'); ?></strong>:
		<?php echo htmlentities($model->exception->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
	</p>

	<h2>Stack Trace</h2>
	<pre><?php echo htmlentities($model->exception->getTraceAsString(), ENT_QUOTES, 'UTF-8'); ?></pre>
<?php } ?>>
