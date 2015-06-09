<?php

include './base.php';

if (!isset($_GET['tablename']))
  Location::to('info_tables.php');

try
{
  if (isset($_GET['where_clause']))
    $db->getTable($_GET['tablename'])
      ->deleteEntries(unserialize($_GET['where_clause']));
}
catch (ErrorException $e)
{
  Location::to('info_tables.php');
}

Location::to('content.php?tablename='.urlencode($_GET['tablename']));
