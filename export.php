<?php

include './base.php';

header('Content-type: text');
header('Content-Disposition: attachment; filename=bdd.sql');
echo $db->import();