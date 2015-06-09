<?php

include './base.php';

if (!isset($dbtable))
	Location::to('info_tables.php');

$nbresult = 30;
$count = isset($_GET['count']) ? (int)$_GET['count'] : $nbresult;
if ($count < 1)
	$count = 1;
$rows = $dbtable->getStatus();
$rows = (int)$rows['Rows'];
$pages = round($rows / $nbresult);
$begin = isset($_GET['begin']) ? (int)$_GET['begin'] : 0;
$page = 1;
if (isset($_GET['page']) && ($page = (int)$_GET['page']) > 0)
	$begin = ($page - 1) * $nbresult + 1;

include './view/content.php';
