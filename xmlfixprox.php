<?php

header('Content-Type: application/xml');

$bloggerdata = file_get_contents($_REQUEST['url']);
$bloggerdata = preg_replace('/<(img|meta|link|hr|br)([^<>]*?)([\/]?)>/i','<$1$2 />', $bloggerdata);
$bloggerdata = preg_replace('/&([^;]{10})/i','&amp;$1', $bloggerdata);
$bloggerdata = str_replace('<HEAD>','<head>',$bloggerdata);
$bloggerdata = str_replace('</HEAD>','</head>',$bloggerdata);
echo $bloggerdata;

?>