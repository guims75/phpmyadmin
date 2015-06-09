<?php

include './base.php';

$view = new TemplateBase('post');

if (!isset($_GET['tablename']))
	Location::to('info_tables.php');

function location_content()
{
  Location::to('content.php?tablename='.urlencode($_GET['tablename']));
}

function get_fields(DB $db, DBTable $table = null)
{
  $fields = array();
  if (!$table)
    $table = $db->getTable($_GET['tablename']);
  if (!($result = $table->getStructure()))
    location_content();
  while ($row = $result->fetch_assoc())
    $fields[] = array($row['Field'], $row['Type']);
  return $fields;
}

if ($view->isSubmit())
  {
    unset($_POST['submit']);
    $table = $db->getTable($_GET['tablename']);
    if (isset($_GET['delete']))
      $b = $table->deleteEntry($_POST);
    else if (isset($_GET['edit']))
      $b = $table->update($_POST, unserialize($_GET['edit']));
    else
      $b = $table->insert($_POST);
    if ($b)
      location_content();
    if (isset($_GET['edit']))
      $edit = $_GET['edit'];
    $view->error = $db->getError();
    $fields = get_fields($db, $table);
  }
else if (isset($_GET['where_clause']))
  {
    if (isset($_GET['edit']))
      $edit = $_GET['where_clause'];
    $_POST = unserialize($_GET['where_clause']);
    $fields = get_fields($db);
  }
else
  location_content();

include './view/copy_field.php';
