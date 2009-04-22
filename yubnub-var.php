<?php

header('Content-Type: text/plain;charset=utf-8');

if(!$_REQUEST['var']) {
   header('Location: http://yubnub.org/parser/parse?command=man%20var',TRUE,303);
   exit;
}//end if ! var

$query = XN_Query::create('Content')
         ->filter('owner','=')
         ->filter('type','eic','var')
         ->filter('title','eic',$_REQUEST['var']);
$items = $query->execute();
if($items && count($items))
   $item = $items[0];
else
   $item = XN_Content::create('var',$_REQUEST['var']);

if($_REQUEST['set'] != 'do not set : tes ton od') {
   $item->set('description',$_REQUEST['set']);
   $item->saveAnonymous();
   sleep(2);
   $item->saveAnonymous();   
}//end if set

echo $item->description;

?>