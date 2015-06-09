<?php

include './includes/header.php';
?>

<div id="page_principale">

<?php
  if (isset($err))
    echo "<p>$err</p>";
?>

<form method="post" action="field_drop.php">
	<p>
		<input type="hidden" value="<?php echo $_POST['field']; ?>" name="field" />
		<input type="hidden" value="<?php echo $_POST['tablename']; ?>" name="tablename" />
		<input type="submit" value="Supprimer le champ <?php echo $_POST['tablename'].'.'.$_POST['field']; ?>" />
	</p>
</form>
</div>

<?php include './includes/footer.php' ?>