<?php

include './php/autoloader.php';

$view = new TemplateBase('post');

if ($view->isSubmit(array('host', 'user', 'passwd')))
{
	try
	{
		$host = $view->getField('host');
		$user = $view->getField('user');
		$passwd = $view->getField('passwd');
		DB::getFactory($host, $user, $passwd);
		session_start();
		$_SESSION['host'] = $host;
		$_SESSION['user'] = $user;
		$_SESSION['passwd'] = $passwd;
		Location::to('ctrl_create_db.php');
	}
	catch(Exception $e)
	{
		$view->error = $e->getMessage();
		include './view/index.php';
	}
}
else
{
	$host = 'localhost';
	$user = 'root';
	include './view/index.php';
}
?>