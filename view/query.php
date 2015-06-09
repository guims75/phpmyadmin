<?php


include 'includes/header.php' ?>

<div id="principal">

  <?php
  if ($view->error)
    $view->showError();
  else if ($view->result && $view->result instanceof MySQLi_Result)
    {
      if ($row = $view->result->fetch_assoc())
	{
	  echo '<table><tr><th>'.implode('</th><th>',
					 array_keys($row)).'</th></tr>';
	  do
	    {
	      echo '<tr>';
	      foreach ($row as $v)
		echo '<td>'.htmlentities($v).'</td>';
	      echo '</tr>';
	    } while ($row = $view->result->fetch_assoc());
	  echo '</table>';
	}
    }
?>

<form action="query.php" method="<?php echo $view->getMethod(); ?>">
  <fieldset>
  <legend>Executer une requ&ecirc;te SQL</legend>
  <textarea rows="20" cols="100" name="query"><?php echo $view->query; ?></textarea>
<?php $view->showSubmit() ?>
  </fieldset>
  </form>
  </div>
<?php include 'includes/footer.php' ?>
