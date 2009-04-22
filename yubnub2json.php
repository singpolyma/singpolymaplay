<?php

XN_Application::includeFile('xoxotools','/OutlineClasses/Outline.php');

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

header('Content-Type: text/javascript;charset=utf-8');

$struct = new Outline();
$struct->addField('cmd',$_REQUEST['cmd']);
if(!isset($_REQUEST['nodata']))
   $struct->addField('data',yubnubcmd($_REQUEST['cmd']));
$struct->addField('url',yubnubcmd('url '.$_REQUEST['cmd']));

if($_REQUEST['callback'])
   echo $_REQUEST['callback'].'(';
echo $struct->toJSON();
if($_REQUEST['callback'])
   echo ')';

?>