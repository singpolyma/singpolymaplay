<?php

if(!$_REQUEST['url']) die('<h1>No URL Given!</h1>');

header('Content-type: text/javascript;charset=utf8');

require_once 'xn-app://singpolymaplay/getTidy.php';
require_once 'xn-app://xoxotools/extract_by_class.php';

$page = getTidy($_REQUEST['url']);
$result = extract_by_class($page,'entry-summary');

if($_REQUEST['callback'])
   echo $_REQUEST['callback'].'(';

echo '{"summary":"'.str_replace("\n",'\n',str_replace("\r",'\n',addslashes($result[0]))).'", "url":"'.str_replace("\n",'\n',str_replace("\r",'\n',addslashes($_REQUEST['url']))).'"}';

if($_REQUEST['callback'])
   echo ')';

?>