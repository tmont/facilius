<?php $this->setParent(__DIR__ . '/../shared/layout.php'); ?>

<?php $this->section('title', true); ?>
OH HAI!

<?php $this->section('body'); ?>
		<p>
			This is the body. How do you like it?
		</p>

		<form method="post" action="/home/form">
			<label for="foo">Foo:</label><input type="text" name="foo" id="foo" /><br />
			<label for="bar">Bar:</label><input type="text" name="bar" id="bar" /><br />
			<input type="submit" />
		</form>
