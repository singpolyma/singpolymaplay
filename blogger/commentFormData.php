<?php

function errhandle() {}
set_error_handler(errhandle);

require_once 'xn-app://xoxotools/OutlineClasses/Outline.php';

header('Content-Type: text/javascript;charset=utf-8');

$page = file_get_contents($_REQUEST['url']);

preg_match('/alt="Visual verification"[^<>]*src="([^<>]*)">/',$page,$result);
$final['captcha'] = 'https://beta.blogger.com'.html_entity_decode($result[1]);

preg_match('/name="securityToken"[^<>]*value="([^<>]*)">/',$page,$result);
$final['securitytoken'] = html_entity_decode($result[1]);

if($_REQUEST['callback'])
   echo $_REQUEST['callback'].'(';

$final = new Outline($final);
echo $final->toJSON();

if($_REQUEST['callback'])
   echo ')';

?>