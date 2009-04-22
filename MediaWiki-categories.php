<?php

//need filter (if we ever do count)

if(!$_REQUEST['mainpage'])
   die('<h2>Please specify a main page!</h2>');

require_once 'getTidy.php';
require_once 'xn-app://xoxotools/OutlineClasses/OutlineFromXOXO.php';

$page = getTidy(str_replace('Main_Page','Special:Categories?limit=9999',$_REQUEST['mainpage']));

$xoxo = new OutlineFromXOXO($page,array('classes' => array('special')));

header('Content-type: text/javascript;charset=utf-8');

if($_REQUEST['callback'])
   echo $_REQUEST['callback'].'(';

echo '{';

//Count is zero because we don't know
foreach($xoxo->getNodes() as $id => $item) {
   if($id != 0) echo ', ';
   echo '"'.addslashes(str_replace("\n",' ',str_replace("\r",'',$item->getField('text')))).'":0';
}//end foreach

echo '}';

if($_REQUEST['callback'])
   echo ')';

?>