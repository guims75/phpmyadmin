<?php

include './php/Session.php';

session_start();

Session::destroy();

header('Location:index.php');
