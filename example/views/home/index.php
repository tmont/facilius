<?php $this->setParent(__DIR__ . '/../shared/layout.php'); ?>

<?php $this->section('title', true); ?>
OH HAI!

<?php $this->section('body'); ?>
		<p>
			Just a simple form test that uses model binding on the backend.
			"foo" will coerce to a string and "bar" will coerce to an int.
		</p>

		<form method="post" action="<?php echo $html->actionUrl('form', 'home'); ?>">
			<table>
				<tr>
					<th><label for="foo">Foo (string):</label></th>
					<td><input type="text" name="foo" id="foo"/></td>
				</tr>
				<tr>
					<th><label for="bar">Bar (integer):</label></th>
					<td><input type="number" name="bar" id="bar"/></td>
				</tr>
				<tr>
					<td></td>
					<td style="text-align: right"><input type="submit"/></td>
				</tr>
			</table>
		</form>
