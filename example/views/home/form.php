<?php $this->setParent(__DIR__ . '/../shared/layout.php'); ?>


<?php $this->section('title', true); ?>
Form Test

<?php $this->section('body'); ?>

	<table>
		<tr>
			<th>Foo</th>
			<td><?php echo $model->foo ?></td>
		</tr>
		<tr>
			<th>Bar</th>
			<td><?php echo $model->bar ?></td>
		</tr>
	</table>
