<?php

function exception_error_handler($errno, $errstr, $errfile, $errline)
{
  throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

set_error_handler('exception_error_handler');

function put_error($err, $context = 'my_phpmyadmin')
{
  echo $context.': '.$err."\n";
}

function get_exception_error(Exception $e)
{
  $m = $e->getMessage();
  return substr($m, strrpos($m, ':') + 2);
}

function put_exception_error(Exception $e, $context = 'my_phpmyadmin')
{
  put_error(get_exception_error($e), $context);
}

function __autoload($name)
{
  include './php/'.$name.'.php';
}