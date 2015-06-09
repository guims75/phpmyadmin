<?php

$noloadbase = true;
include './base.php';

$view = new TemplateBase('post');

if ($view->isSubmit(array('dbname')))
  {
    $dbname = $view->getField('dbname');
    if ($db->createBase($dbname))
      {
	$_SESSION['dbname'] = $dbname;
	Location::to('./ctrl_create_table.php');
      }
    else
      {
	$view->error = $db->getError();
	include './view/createdb.php';
      }
  }
else
  include './view/createdb.php';
