<?php

$_REQUEST['titledesc'] = $_REQUEST['titledesc'] ? urldecode($_REQUEST['titledesc']) : ' - ';
$_REQUEST['as'] = $_REQUEST['as'] ? $_REQUEST['as'] : 'text';

$query = XN_Query::create('Content')
         ->filter('owner','=')
         ->filter('type','eic','var')
         ->order('title','asc');

if($_REQUEST['match'])
   $query->filter('title','likeic',$_REQUEST['match']);

$items = $query->execute();

$echoed = array();
foreach($items as $item) {
   if($_REQUEST['removematch']) $item->title = str_replace($_REQUEST['match'],'',$item->title);
   if($_REQUEST['removecolon']) $item->title = str_replace(':','',$item->title);
   $item->title = urldecode($_REQUEST['prefix']).$item->title;
   if($_REQUEST['content']) $item->title .= $_REQUEST['titledesc'].$item->description;
   if(in_array($item->title,$echoed)) {XN_Content::delete($item); continue;}
   $echoed[] = $item->title;
}//end foreach

require('php2yubnubarray.php');

echo php2yubnubarray($echoed,$_REQUEST['as'],$_REQUEST['callback']);

?>