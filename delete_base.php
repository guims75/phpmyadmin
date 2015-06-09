<?php

include './base.php';

if (isset($_POST['dropdb']))
  {
    if ($db->deleteBase($_POST['dropdb']))
      {
	if (isset($_SESSION['dbname']) &&
	    $_SESSION['dbname'] === $_POST['dropdb'])
	  unset($_SESSION['dbname']);
	Location::to('ctrl_create_db.php');
      }
    $err = $db->getError();
  }

include './view/delete_base.php';