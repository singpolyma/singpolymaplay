<?php

error_reporting(0);

$error = false;
function handlerr($errno, $errmsg, $filename, $linenum, $vars) {
   if($errno == 8 || $errno == 2048) return;
   global $error;   
   $error[] = array('number' => $errno, 'message' => $errmsg);
}//end function handlerr

set_error_handler("handlerr");

if($_REQUEST['url']) {
   $curl = curl_init($_REQUEST['url']);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
   $_REQUEST['string'] = curl_exec($curl);
   curl_close($curl);
}//end if url

$_REQUEST['pattern'] = urldecode($_REQUEST['pattern']);
$array = array();
$matches = preg_match_all($_REQUEST['pattern'],$_REQUEST['string'],$array);
if($_REQUEST['capture'] == 'auto')
   $_REQUEST['capture'] = count($array[1]) ? true : false;
$idx = $_REQUEST['capture'] ? 1 : 0;

if($_REQUEST['nbrmatches']) {
   header('Content-Type: text/plain;charset=utf-8');
   echo $matches;
   exit;
}//end if nbrmatches

if($_REQUEST['matchnbr']) {
   header('Content-Type: text/plain;charset=utf-8');
   echo $array[$idx][$_REQUEST['matchnbr']-1];
   exit;
}//end if matchnbr

require('php2yubnubarray.php');
$_REQUEST['as'] = $_REQUEST['as'] ? $_REQUEST['as'] : 'xml';
$output = php2yubnubarray($array[$idx],$_REQUEST['as'],$_REQUEST['callback']);
if($error)
   $output = php2yubnubarray(array($error[0]['number'].' : '.$error[0]['message']),$_REQUEST['as'],$_REQUEST['callback']);
echo $output;

?>