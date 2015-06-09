<?php

?>

<div id="navigation">
	<ul >
		<?php if ($db->getDBName()) : ?>
		<li class="gauche"><a href="info_tables.php" title="structure de la base">Info tables</a></li>
		<?php endif;?>
		<li class="gauche"><a href="ctrl_create_db.php" title="creer une table">Bases de donn&eacute;es</a></li>
		<li class="gauche"><a href="query.php" title="faire des requete sql">Requ&ecirc;tes SQL</a></li>
		<li class="gauche"><a href="import.php" title="importer/exporter la table">Importer/Exporter</a></li>
		<li class="droite"><a href="disconnect.php" title="se deconnecter">D&eacute;connexion</a></li>
	</ul>
</div>
<div id="centre">

		<div id="secondaire">
			<ul>
		<?php
		if ($dbname = $db->getDBName())
		{
			foreach ($db->getTableNames() as $tablename)
				echo '<li><a href="structure.php?tablename='.urlencode($tablename).'"'
				.(isset($dbtablename) && $dbtablename === $tablename ? ' class="selected"' : '')
				.'>'.$tablename.'</a></li>';
		}
		else
		{
			foreach ($db->getDBNames() as $dbname)
				echo '<li><a href="info_tables.php?dbname='.urlencode($dbname).'"'
				.($db->getDBName() === $dbname ? ' class="selected"' : '')
				.'>'.$dbname.'</a></li>';
		}
		?>
			</ul>
		</div><!-- #principal -->

