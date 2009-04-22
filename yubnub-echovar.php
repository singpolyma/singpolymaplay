<?php

if(!$_REQUEST['content-type'])
   $_REQUEST['content-type'] = 'text/html';
header('Content-Type: '.$_REQUEST['content-type'].';charset=utf-8');

$query = XN_Query::create('Content')
         ->filter('owner','=')
         ->filter('type','eic','var')
         ->filter('title','eic',$_REQUEST['var']);
$items = $query->execute();

if($items && count($items))
   $item = $items[0];
else
   $item = XN_Content::create('var',$_REQUEST['var']);

$_REQUEST['txt'] = str_replace('@var@',$item->description,$_REQUEST['txt']);

echo $_REQUEST['txt'];

?>