<?php

function yubnubcmd($cmd) {
      $curl = curl_init('http://yubnub.org/parser/parse?command='.urlencode($cmd));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
   $rtrn = curl_exec($curl);
   curl_close($curl);
   return $rtrn;
}//end function yubnubcmd

header('Content-Type: text/plain;charset=utf-8');
require('yubnub2phparray.php');
$items = yubnub2phparray(yubnubcmd('var '.$_REQUEST['var']));
$items[] = $_REQUEST['append'];

require('php2yubnubarray.php');
echo yubnubcmd('var '.$_REQUEST['var'].' -set '.php2yubnubarray($items));

?>