<?php

require('yubnub2phparray.php');
$items = yubnub2phparray($_REQUEST['data']);
unset($items[$_REQUEST['idx']]);
$items = array_values($items);

require('php2yubnubarray.php');
$_REQUEST['as'] = $_REQUEST['as'] ? $_REQUEST['as'] : 'xml';
echo php2yubnubarray($items,$_REQUEST['as'],$_REQUEST['callback']);

?>