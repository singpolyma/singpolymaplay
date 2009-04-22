<?php

$_REQUEST['as'] = $_REQUEST['as'] ? $_REQUEST['as'] : 'xml';
$_REQUEST['token'] = str_replace('<space>',' ',$_REQUEST['token']);
$array = explode($_REQUEST['token'],$_REQUEST['data']);

require('php2yubnubarray.php');

echo php2yubnubarray($array,$_REQUEST['as'],$_REQUEST['callback']);

?>