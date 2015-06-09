<?php


if (!isset($fields, $_GET['tablename']))
	Location::to('info_tables.php');
include 'includes/header.php';
?>
<div id="principal">
<?php
	$view->showError();
	echo '<form action="copy_field.php?tablename='
		.urlencode($_GET['tablename'])
		.(isset($_GET['delete']) ? '&amp;delete=' : '')
		.(isset($_GET['edit']) ? '&amp;edit='.urlencode($edit) : '')
		.'" method="'.$view->getMethod().'">';
	foreach ($fields as $info)
	$view->showField($info[0], 'text', $info[0].' '.$info[1].' : ');
	$view->showSubmit();
	echo '</form>';
?>

</div>

<?php
  include 'includes/footer.php';
?>