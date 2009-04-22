<?php

function yubnubcmd($cmd) {
   if($cmd{0} == '"')
      return substr($cmd,1,strlen($cmd)-2);
   $curl = curl_init('http://yubnub.org/parser/parse?command='.urlencode($cmd));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
   $rtrn = curl_exec($curl);
   curl_close($curl);
   return $rtrn;
}//end function yubnubcmd

require('yubnub2phparray.php');
require('php2yubnubarray.php');

$items = yubnub2phparray($_REQUEST['data']);

$_REQUEST['cmd'] = str_replace('%25s','%s',$_REQUEST['cmd']);
$cmdsep = $_REQUEST['as'] == '<space>' ? ' ' : "\n";
if($_REQUEST['as'] == 'null') $cmdsep = '';

foreach($items as $item) {
   $cmd = stristr($_REQUEST['cmd'],'%s') ? str_replace('%s',$item,$_REQUEST['cmd']) : $_REQUEST['cmd'].' '.$item;
   $cmd = str_replace('[|','{',$cmd);
   $cmd = str_replace('|]','}',$cmd);
   if($_REQUEST['as'] != 'array')
      $data .= yubnubcmd($cmd).$cmdsep;
   else
      $data[] = yubnubcmd($cmd);
}//end foreach items

$_REQUEST['type'] ? $_REQUEST['type'] : $_REQUEST['type'] = 'xml';

if($_REQUEST['as'] != 'array') {
   header('Content-Type: text/plain;charset=utf-8');
   echo $data;
} else
   echo php2yubnubarray($data,$_REQUEST['type'],$_REQUEST['callback']);

?>