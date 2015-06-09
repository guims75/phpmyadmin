<?php

$dbname = $db->getDBName();

if (!$dbname)
  Location::to('ctrl_create_db.php');
include './includes/header.php';
?>
<div id="principal">
<?php
  if (isset($err))
    echo "<p>$err</p>";
?>

<form method="post" action="delete_base.php?dbname=<?php echo urlencode($dbname); ?>">
	<p>
		<input type="hidden" value="<?php echo $dbname ?>" name="dropdb" />
		<input type="submit" value="Supprimer la base <?php echo $dbname ?>" />
	</p>
</form>
</div>

<?php include './includes/footer.php' ?>