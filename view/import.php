<?php


include 'includes/header.php';
?>
<div id="principal">
<?php
if (isset($err))
  echo "<p>$err</p>";
?>

<form action="import.php" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>Importer une base de donn&eacute;es</legend>
		<p>
			<label for="sql">Base de donn&eacute;es (.sql)</label>
			<input type="file" name="sql" />
		</p>
	</fieldset>
	<p><input type="submit" value="Envoyer" /></p>
 </form>
 <p><a href="export.php">Exporter la bdd</a>.</p>
 </div>

<?php
include 'includes/footer.php';
?>