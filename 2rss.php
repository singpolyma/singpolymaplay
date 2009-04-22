<?php

header('Content-Type: application/xml');
$data = file_get_contents($_REQUEST['url']);

?>
<rss version="2.0">
   <channel>
   <title><?php echo htmlspecialchars($_REQUEST['url']); ?></title>
   <link><?php echo htmlspecialchars($_REQUEST['url']); ?></link>
   <description></description>
   <docs>http://blogs.law.harvard.edu/tech/rss</docs>
   <generator>PHP script</generator>

<?php

$struct = explode('<p>',$data);
foreach($struct as $id => $item) {
   if($id == count($struct)-1) continue;
   if($item{0} == '>') $item = substr($item,1,strlen($item));
   $link = array();
   preg_match('/<a .*?href="(.*?)"/',$item,$link['url']);
   $link['url'] = $link['url'][1];
   preg_match('/<a.*?>(.*?)<\/a>/',$item,$link['text']);
   $link['text'] = $link['text'][1];
   echo '   <item>'."\n";
   echo '      <title>'.htmlspecialchars(strip_tags($link['text'])).'</title>'."\n";
   echo '      <link>'.htmlspecialchars($link['url']).'</link>'."\n";
   echo '      <description>'.htmlspecialchars($item).'</description>'."\n";
   echo '   </item>'."\n";
}//end foreach struct

?>

   </channel>
</rss>