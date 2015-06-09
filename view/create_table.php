<?php

include './includes/header.php';
?>

<div id="principal">
	<?php $view->showError(); ?>
	<form action="ctrl_create_table.php" method="<?php echo $view->getMethod(); ?>">
		<fieldset>
			<legend>Espace base de donn&eacute;es</legend>

			<?php
				$view->showField('tablename', 'text', 'Cr&eacute;er une table : ');
				$view->showField('nbfields', 'text', 'Nombre de champs : ');
			?>
		</fieldset>
		<?php $view->showSubmit(); ?>
	</form>
</div>

<?php
  include './includes/footer.php';
?>