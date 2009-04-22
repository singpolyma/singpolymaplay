<?php

require_once 'xn-app://xoxotools/proxy/normalize_url.php';
header('Content-type: text/plain;charset=utf-8');
echo normalize_url($_REQUEST['url']);

?>