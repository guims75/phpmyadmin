<?php

include './base.php';

if (isset($_POST['droptable']))
{
	if ($db->deleteTable($_POST['droptable']))
		Location::to('info_tables.php');
	$err = $db->getError();
}

include './view/drop_table.php';