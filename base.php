<?php

include './php/autoloader.php';

session_start();

if (empty($_SESSION))
  {
    Session::destroy();
    Location::to('disconnect.php');
  }

if (isset($noloadbase) && $noloadbase)
  {
    if (isset($_SESSION['dbname']))
      unset($_SESSION['dbname']);
  }
else  if (isset($_GET['dbname']))
  $_SESSION['dbname'] = $_GET['dbname'];

/** @var DB */
try
{
  $db = DB::getFactory(
		       $_SESSION['host'],
		       $_SESSION['user'],
		       $_SESSION['passwd'],
		       isset($_SESSION['dbname']) ? $_SESSION['dbname'] : ''
		       );
}

catch (ErrorException $e)
{
  try
    {
      $_SESSION['dbname'] = null;
      $db = DB::getFactory(
			   $_SESSION['host'],
			   $_SESSION['user'],
			   $_SESSION['passwd']
			   );
    }

  catch (ErrorException $e)
    {
      Location::to('index.php');
    }
}

if (isset($_GET['tablename']))
  {
    $dbtablename = $_GET['tablename'];
    $dbtable = $db->getTable($dbtablename);
  }
