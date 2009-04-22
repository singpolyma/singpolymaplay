<?php

if(!$_REQUEST['url'])
   die('<h2>No URL Specified!</h2>');

if(!$_REQUEST['name'])
   die('<h2>No Name Specified!</h2>');

require_once 'xn-app://someblogs/normalize_url.php';

$page = file_get_contents($_REQUEST['url']);

header('Content-type: text/javascript;charset=utf8');

preg_match_all('/<a[^<>]*?href='."['\"]".'([^<>]*?)'."['\"]".'[^<>]*?>\s*?'.$_REQUEST['name'].'\s*?<\/a>/i',$page,$result);

if($_REQUEST['callback'])
   echo $_REQUEST['callback'].'(';

if($_REQUEST['parameter'])
   echo $_REQUEST['parameter'].', ';

if($result[1][1])
   echo '{"url":"'.normalize_url($result[1][1]).'"}';
else if($result[1][0])
   echo '{"url":"'.normalize_url($result[1][0]).'"}';
else
   echo '{"url":""}';

if($_REQUEST['callback'])
   echo ')';

?>