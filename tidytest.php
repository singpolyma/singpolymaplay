<?php

require('getTidy.php');

header('Content-type: application/xhtml+xml');

echo getTidy($_REQUEST['url']);

?>