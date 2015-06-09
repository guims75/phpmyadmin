<?php


if (!$db->getDBName()) :
	include 'info_bases';
else :
	include 'includes/header.php';
?>
<div id="principal">
<?php
$result = $db->getStatus();
if ($row = $result->fetch_assoc())
  {
    ?>
    <table>
      <tr>
      <th>Table</th>
      <th>Action</th>
      <th>Lignes</th>
      <th>Type</th>
      <th>Collation</th>
      <th>Taille</th>
      <th>Overhead</th>
      </tr>
      <?php
      do
	{
	  $table = $row['Name'];
	  $get = 'tablename='.urlencode($table);
	  ?>
	  <tr>
	     <td><?php echo $table?></td>
	     <td> <a href="content.php?<?php echo $get ?>">Contenu</a>
	     <a href="structure.php?<?php echo $get ?>">Structure</a>
	     <a href="copy_field.php?<?php echo $get ?>&amp;where_clause=a%3A0%3A%7B%7D">Insert</a>
	     <a href="free_table.php?<?php echo $get ?>">Vider</a>
	     <a href="drop_table.php?<?php echo $get ?>">Supprimer</a></td>
	     <td><?php echo $row['Rows']?></td>
	     <td><?php echo $row['Engine']?></td>
	     <td><?php echo $row['Collation']?></td>
	     <td><?php echo $row['Index_length']?> B</td>
	     <td><?php $free = $row['Data_free']; echo $free ? $free.' B' : '-' ?></td></tr>
						    <?php
						    }
      while ($row = $result->fetch_assoc());
  }
else
  echo '<p>Aucune table</p>';
?>

</table>
<p><a href="ctrl_create_table.php">Créer une table</a></p>
  </div>
  <?php
  endif;
include 'includes/footer.php';
?>