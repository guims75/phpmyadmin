<?php


include './base.php';

$view = new TemplateBase('post');

if (isset($_POST['field'], $_POST['tablename']))
{
	if ($db->getTable($_POST['tablename'])
		->deleteField($_POST['field']))
		Location::to('structure.php?tablename='
		.urlencode($_POST['tablename']));
	$err = $db->getError();
}
else if (isset($_GET['field'], $_GET['tablename']))
{
	$_POST = array('field' => $_GET['field'],
		'tablename' => $_GET['tablename']);
}
else
	Location::to('info_tables.php');

include './view/field_drop.php';

