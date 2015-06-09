<?php

include './base.php';

$view = new TemplateBase;

if ($view->isSubmit(array('query')) &&
	!($view->result = $db->query($view->getField('query'))))
	$view->error = $db->getError();

include './view/query.php';
