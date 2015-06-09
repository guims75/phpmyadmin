<?php

include './base.php';

if (!isset($_GET['tablename']))
	Location::to('ctrl_create_table.php');

$view = new TemplateBase('post');
$info_property = array('nom', 'field_type', 'default', 'taille',
		       'index', 'comment');

if ($view->isSubmit($info_property))
  {
    $field_type = $view->getField('field_type');
    $default = $view->getField('default');
    $taille = $view->getField('taille');
    $null = $view->isExist('null') ? $view->getField('null') : array();
    $index = $view->getField('index');
    $autoincr = $view->isExist('autoincr') ? $view->getField('autoincr')
      : array();
    $comment = $view->getField('comment');
    $property = array();
    $attribut = array();
    foreach($view->getField('nom') as $key => $name)
      {
	if ($name === ''
	    && ($taille[$key] !== '' || isset($null[$key])
		|| isset($autoincr[$key]) || $comment[$key] !== ''))
	  $view->error = 'You have to renseign the name of the field !';
	elseif ($name !== '')
	  {
		  $extra = ($index[$key] !== 'normal') ? $index[$key] : '';
		if (isset($autoincr[$key]))
			$extra .= ' '.$autoincr[$key];
	    $property = array('type' => $field_type[$key],
			      'extra' => $extra,
			      'com' => $comment[$key]);
		if ($taille[$key] !== '')
			$property['type'] .= '('.$taille[$key].')';
		if (isset($null[$key]))
	      $property['null'] = true;
		if ($default[$key] !== 'normal')
			$property['default'] = $default[$key];
		$attribut[$name] = $property;
	  }
      }
	if (!empty($attribut))
	{
		if (isset($_GET['add']) || isset($_GET['edit']))
		{
			if (isset($_GET['add']))
			{
				if ($db->getTable($_GET['tablename'])->createFields($attribut))
					Location::to('structure.php?tablename='.$_GET['tablename']);
			}
			else
			{
				foreach ($attribut as $field => $property)
				{
					if ($db->getTable($_GET['tablename'])->changeField($_GET['edit'], $field, $property))
						Location::to('structure.php?tablename='.$_GET['tablename']);
					break;
				}
			}
		}
		else if ($db->createTable($_GET['tablename'], $attribut))
			Location::to('ctrl_create_table.php');
		$view->error = $db->getError();
	}
  }

include './view/fields.php';