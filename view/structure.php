<?php


if (!isset($dbtablename))
	Location::to('info_tables.php');
include './includes/header.php';
echo '<div id="principal">';
if (!($result = $dbtable->getStructure()))
	echo '<p>'.$db->getError().'</p>';
else
  {
    $getdbtablename = 'tablename='.urlencode($dbtablename);
    if ($row = $result->fetch_assoc())
      {
	?>

	<table>
		<tr>
			<th>Colonne</th>
			<th>Type</th>
			<th>Attributs</th>
			<th>Null</th>
			<th>D&eacute;faut</th>
			<th>Extra</th>
			<th>Action</th>
		 </tr>

		<?php
			do
			   {
			     $p = strpos($row['Type'], ' ');
			     $row['Attr'] = $p === false ? '' : substr($row['Type'], $p + 1);
			     $row['Type'] = $p === false ? $row['Type'] : substr($row['Type'], 0, $p);
			     $row['Null'] = $row['Null'] === 'NO' ? 'No' : 'Yes';
			     $row['Default'] = $row['Default'] === null ? 'Null' : $row['Default'];
				 $tmpField = urlencode($row['Field']);
				 $get = $getdbtablename.'&amp;field='.$tmpField;
			     echo '<tr>';
			     foreach (array('Field', 'Type', 'Attr', 'Null', 'Default', 'Extra') as $k)
			       echo '<td>'.htmlentities($row[$k]).'</td>';
			     echo '<td><a href="ctrl_fields.php?'.$get
				.'&amp;edit='.$tmpField.'">changer</a> <a href="field_drop.php?'
				.$get.'">supprimer</a></td></tr>';
			   }
			while ($row = $result->fetch_assoc());
			echo '</table>
				<div>
					<form method="get" action="ctrl_fields.php">
						<p>
							<input type="hidden" value="'.$dbtablename.'" name="tablename" />
							<input type="hidden" value="" name="add" />
							<label for="nbfields">Ajouter combien de champ : </label>
							<input type="text" name="nbfields" />
							<input type="submit" value="ajouter"/>
						</p>
					</form>
				</div>
				<h2>Index</h2>';
			$indexes = $dbtable->getInfoIndex();
			$keys = array();
			while ($row = $indexes->fetch_assoc())
				$keys[$row['Key_name']][] = $row;
			if (!empty($keys))
			{
		?>

				<table>
					<tr>
						<th>Action</th>
						<th>Keyname</th>
						<th>Type</th>
						<th>Unique</th>
						<th>Packed</th>
						<th>Column</th>
						<th>Cardinality</th>
						<th>Collation</th>
						<th>Null</th>
						<th>Comment</th>
					</tr>

				<?php foreach ($keys as $name => &$infos): ?>

					<tr>
						<td></td>
						<td><?php echo $name ?></td>
						<td><?php echo $infos[0]['Index_type'] ?></td>
						<td><?php echo $infos[0]['Seq_in_index'] === 1 ? 'Yes' : 'No' ?></td>
						<td><?php echo $infos[0]['Packed'] ?: '' ?></td>
						<td><?php foreach ($infos as $k => $info)
							echo ($k ? ',' : '').$info['Column_name']; ?></td>
						<td><?php echo $infos[0]['Cardinality'] ?></td>
						<td><?php echo $infos[0]['Collation'] ?></td>
						<td><?php echo $infos[0]['Null'] ?></td>
						<td><?php echo $infos[0]['Comment'] ?></td>
					</tr>

				<?php
					endforeach;
					echo '</table>';
			}
      }
    else
      echo '<p>Aucun champ</p>';
  }
echo '</div>';
include './includes/footer.php';
