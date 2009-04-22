<?php

if($_REQUEST['get'])
   $_REQUEST['data'] = file_get_contents($_REQUEST['get']);

if(!$_REQUEST['data']) {
   header('Content-Type: text/plain');
   header('Location: http://yubnub.org/kernel/man?args=mkrss',TRUE,303);
   exit;
}//end if ! data

header('Content-Type: application/xml;charset=utf-8');
$_REQUEST['title'] = $_REQUEST['title'] ? $_REQUEST['title'] : ($_REQUEST['url'] ? $_REQUEST['url'] : 'A mkrss feed');
$_REQUEST['url'] = $_REQUEST['url'] ? $_REQUEST['url'] : 'http://yubnub.org/kernel/man?args=mkrss';
require('yubnub2phparray.php');
$items = yubnub2phparray($_REQUEST['data']);

echo '<?xml version="1.0" ?>'."\n";

?>
<rss version="2.0">
   <channel>
   <title><?php echo htmlspecialchars(iconv('', 'UTF-8', $_REQUEST['title'])); ?></title>
   <link><?php echo htmlspecialchars(iconv('', 'UTF-8', $_REQUEST['url'])); ?></link>
   <description>Powered by mkrss - a YubNub command</description>
   <docs>http://blogs.law.harvard.edu/tech/rss</docs>
   <generator>PHP script</generator>

<?php

foreach($items as $item) {
   $link = array();
   preg_match('/<a .*?href="(.*?)"/',$item,$link['url']);
   $link['url'] = $link['url'][1];
   preg_match('/<a.*?>(.*?)<\/a>/',$item,$link['text']);
   $link['text'] = $link['text'][1];
   if(!strip_tags($link['text'])) $link['text'] = $item;
   echo '   <item>'."\n";
   echo '      <title>'.htmlspecialchars(strip_tags($link['text'])).'</title>'."\n";
   if($link['url']) {
      echo '      <link>'.htmlspecialchars(iconv('', 'UTF-8', $link['url'])).'</link>'."\n";
      echo '      <guid>'.htmlspecialchars(iconv('', 'UTF-8', $link['url'])).'</guid>'."\n";
   } else
      echo '      <guid>'.md5($link['text'].$item).'</guid>'."\n";      
   echo '      <description>'.htmlspecialchars(iconv('', 'UTF-8', $item)).'</description>'."\n";
   echo '   </item>'."\n";
}//end foreach struct

?>

   </channel>
</rss>