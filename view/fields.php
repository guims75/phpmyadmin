<?php


include './includes/header.php';
?>
<div id="principal">
<?php
$view->showError();
$nbfields = isset($_GET['nbfields']) ? (int)$_GET['nbfields'] : 1;
?>
<form action="ctrl_fields.php?nbfields=<?php echo $nbfields;
?>&amp;tablename=<?php echo urlencode($_GET['tablename']);
	if (isset($_GET['add']))
	echo '&amp;add=';
else if (isset($_GET['edit']))
	echo '&amp;edit='.urlencode($_GET['edit']);
?>"
	method="<?php echo $view->getMethod(); ?>">
	<fieldset>
		<legend>Creation des champs de la table</legend>
		<table>
			<tr>
				<td>Champ : </td>
				<td>Type : </td>
				<td>Default : </td>
				<td>Taille/Valeurs : </td>
				<td>Null : </td>
				<td>Index: </td>
				<td>Auto-incr&eacute;mentation: </td>
				<td>Commentaires : </td>
			</tr>

	<?php
		for ($i = 0; $i < $nbfields; $i++)
			include 'field_type.php';
      	?>

		</table>
	</fieldset>
	<?php $view->showSubmit(); ?>
</form>
</div>

<?php
include './includes/footer.php';
?>