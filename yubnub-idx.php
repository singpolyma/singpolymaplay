<?php

header('Content-Type: text/plain;charset=utf-8');
require('yubnub2phparray.php');
$items = yubnub2phparray($_REQUEST['data']);
echo $items[$_REQUEST['idx']];

?>