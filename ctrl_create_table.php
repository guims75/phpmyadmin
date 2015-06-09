<?php

include './base.php';

$view = new TemplateBase('post');

if ($view->isSubmit(array('tablename', 'nbfields')))
  {
    echo "Y a eu submit.\nchamps non vides.\n";
    if (($tablename = $view->getField('tablename')) === '')
      $view->error = 'You have to write a name for the table !';
    elseif (($nb = (int)$view->getField('nbfields')) < 1)
      $view->error = 'You have to write a number > 0 !';
    else
      Location::to('./ctrl_fields.php?tablename='.urlencode($tablename)
		   .'&nbfields='.$nb);
    include './view/create_table.php';
  }
else
    include './view/create_table.php';
