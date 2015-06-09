<?php


include 'includes/header.php';
?>
<div id="principal">
	<?php $view->showError(); ?>
	<form action="ctrl_create_db.php" method="<?php echo $view->getMethod(); ?>">
		<fieldset>
			<legend>Espace base de donn&eacute;es</legend>
			<?php $view->showField('dbname', 'text', 'Cr&eacute;er une base de donn&eacute;es : '); ?>
		</fieldset>
		<?php $view->showSubmit(); ?>
	</form>


<?php
  echo '<p>';
  foreach ($db->getDBNames() as $dbname)
	echo '<a href="delete_base.php?dbname='.urlencode($dbname).'">Supprimer '.$dbname.'</a><br />';
  echo '</p>';
  include 'includes/footer.php';
?>
</div>