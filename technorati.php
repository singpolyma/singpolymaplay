<?php

header('Content-Type: text/plain');

include 'Services/Technorati.php';

$obj = Services_Technorati::factory('125395fa74934d82fc247a25fde65184');
$obj->cosmos('singpolyma-tech.blogspot.com');
if(!is_array($struct) || !$struct['items']) {var_dump($struct);die;}
foreach($struct['items'] as $item) {
   echo $item['weblog']['name'].'<'.$item['weblog']['name'].'> ';
   echo 'linked to <'.$item['linkurl'].'> ';
   echo 'from <'.$item['nearestpermalink'].'> ';
   echo 'saying ['.$item['excerpt'].'] ';
   echo 'at '.date('r',strtotime($item['linkcreated']));
   echo "\n----\n";
}//end foreach

?>