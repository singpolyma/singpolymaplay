<?php

if(!$_REQUEST['url'])
   die('<h2>No URL Specified!</h2>');

require_once 'getTidy.php';
require_once 'xn-app://xoxotools/extract_by_id.php';
require_once 'xn-app://xoxotools/OutlineClasses/OutlineFromXOXO.php';

$page = getTidy($_REQUEST['url']);
$data = extract_by_id($page,'bodyContent');
$xoxo = new OutlineFromXOXO($data[0],array('classes' => array()));

header('Content-type: text/javascript;charset=utf-8');

if($_REQUEST['callback'])
   echo $_REQUEST['callback'].'(';

echo '[';

foreach($xoxo->getNodes() as $id => $item) {
   if($id > 0) echo ', ';
   echo '{"d":"'.addslashes(str_replace("\n",' ',trim(str_replace("\r",'',$item->getField('text'))))).'", "u":"'.addslashes(trim(str_replace("\n",' ',str_replace("\r",'',$item->getField('href'))))).'"}';
}//end foreach
   
echo ']';

if($_REQUEST['callback'])
   echo ')';

?>