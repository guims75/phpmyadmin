<?php


if (!isset($dbtablename))
	Location::to('info_tables.php');
include './includes/header.php';
echo '<div id="principal">';
$getTablename = 'tablename='.urlencode($dbtablename);
if (!($result = $dbtable->select($begin, $count)))
	echo '<p>'.$db->getError().'</p>';
else
{
	if ($rows && ($row = $result->fetch_assoc()))
	{
		if ($rows > $nbresult):
			if ($page > 1):
				?>
				<form action="content.php" method="get">
					<input type="hidden" name="tablename" value="<?php echo $dbtablename?>" />
					<input type="hidden" name="page" value="<?php echo ($page-1)?>" />
					<input type="submit" value="&lt;&lt;" />
				</form>
				<?php
			endif;
			?>
		<form action="content.php" method="get">
		<p>
			<label for="page">Page </label>
			<select id="page" name="page">

				<?php
				for ($i = 1; $i <= $pages; ++$i)
				{
					if ($page === $i)
						echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
					else
						echo '<option value="'.$i.'">'.$i.'</option>';
				}
				?>

			</select>
			<input type="hidden" name="tablename" value="<?php echo $dbtablename?>" />
			<input type="submit" />
		</p>
		</form>

		<?php if ($page < $pages): ?>
			<form action="content.php" method="get">
				<input type="hidden" name="tablename" value="<?php echo $dbtablename?>" />
				<input type="hidden" name="page" value="<?php echo ($page+1)?>" />
				<input type="submit" value=">>" />
			</form>
			<?php
			endif;
		endif;
		echo '<table><tr><th></th><th>'.implode('</th><th>', array_keys($row)).'</th></tr>';
		do
		{
			$get = $getTablename.'&amp;where_clause='
			.urlencode(serialize($row));
			echo '<tr><td>
				<a href="copy_field.php?'.$get.'&amp;edit=">editer</a>
				<a href="copy_field.php?'.$get.'">copier</a>
				<a href="copy_field.php?'.$get.'&amp;delete=">supprimer</a></td>';
			foreach ($row as $v)
				echo '<td>'.htmlentities($v).'</td>';
			echo '</tr>';
		}
		while ($row = $result->fetch_assoc());
		echo '</table>';
	}
	else
		echo '<p>Aucun enregistrement</p>';
}
echo '<p><a href="copy_field.php?'.$getTablename.'&amp;where_clause=">Ajouter un enregistrement</a></p>
</div>';
include './includes/footer.php';
