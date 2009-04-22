<?php

$cmdstr = urldecode($_REQUEST['cmdstr']);
unset($_REQUEST['cmdstr']);

if(stristr($cmdstr,'%s') || stristr($cmdstr,'${')) {
   $cmdstr = str_replace('%s',$_REQUEST['%s'].$_REQUEST['%25s'],$cmdstr);
   unset($_REQUEST['%s']);
   unset($_REQUEST['%25s']);
   foreach($_REQUEST as $name => $var)
      $cmdstr = str_replace('${'.urldecode($name).'}',$var,$cmdstr);
} else {
   $cmdstr .= ' '.$_REQUEST['%s'].$_REQUEST['%25s'];
   unset($_REQUEST['%s']);
   unset($_REQUEST['%25s']);
   foreach($_REQUEST as $name => $var)
      $cmdstr .= ' -'.urldecode($name).' '.$var;
}//end if stristr

header('Content-Type: text/plain;charset=utf-8');
header('Location: http://yubnub.org/parser/parse?command='.urlencode($cmdstr));

?>