<?php


include './base.php';

if (isset($_POST['freetable']))
{
	if ($db->freeTable($_POST['freetable']))
		Location::to('info_tables.php');
	$err = $db->getError();
}

include './view/free_table.php';