<?php


include '../includes/header.php';
?>
<div id="head">
	<form action="#" method="post">
		<fieldset>
			<legend>Importer une base de donn&eacute;es</legend>
			Base de donn&eacute;es (.csv ou .txt): <input type="file" name="textfield"><br />
			<input type="submit" value="Executer" />
		</fieldset>
	</form>
	<form action="#" method="post">
		<fieldset>
		<title>Exporter une base de donn&eacute;es</title>
			<fieldset>
				<legend>Format</legend>
				<input type="radio" name="fileformat" value="csv" /> CSV<br />
				<input type="radio" name="fileformat" value="txt" /> TXT<br />
				<input type="radio" name="fileformat" value="xml" /> XML<br />
			</fieldset>
			<input type="submit" value="Executer" />
		</fieldset>
	</form>
</div>

<?php
include '../includes/footer.php';
?>