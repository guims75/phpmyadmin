<?php


if (!isset($dbtablename))
	Location::to('info_tables.php');

include './includes/header.php';
?>
<div id="principal">

<?php
if (isset($err))
	echo "<p>$err</p>";
?>

<form method="post" action="drop_table.php">
	<p>
		<input type="hidden" value="<?php echo $dbtablename ?>" name="droptable" />
		<input type="submit" value="Supprimer la table <?php echo $dbtablename ?>" />
	</p>
</form>
</div>

<?php include './includes/footer.php' ?>