<?php

header('Content-Type: text/javascript;charset=utf-8');

   $curl = curl_init('http://www.blogger.com/comment.g?blogID='.urlencode($_REQUEST['blogID']).'&postID='.urlencode($_REQUEST['postID']));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
   $page = curl_exec($curl);
   curl_close($curl);

if($_REQUEST['callback'])
   echo $_REQUEST['callback'].'(';

if(strstr(strip_tags($page),strip_tags($_REQUEST['body'])))
   echo '{"posted":true}';
else
   echo '{"posted":false}';

if($_REQUEST['callback'])
   echo ')';

?>