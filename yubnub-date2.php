<?php

header('Content-type: text/plain;charset=utf-8');

if($_REQUEST['timestamp'])
   echo date($_REQUEST['format'],$_REQUEST['timestamp']);
else
   echo date($_REQUEST['format']);

?>