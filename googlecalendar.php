<?php

require_once 'xn-app://xoxotools/OutlineClasses/Outline.php';
require_once 'xn-app://xoxotools/OutlineClasses/OutlineFromXML.php';
require_once 'xn-app://xoxotools/std_rss_out.php';

if(!$_REQUEST['url']) {
   ?>
<h1>Google Calendar Feed Cleaner</h1>
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>"><div>
   <input type="hidden" name="xn_auth" value="no" />
   Google Calendar Feed URL: <input type="text" name="url" /><br />
   Output Format: <select name="format">
      <option value="xhtml">XHTML</option>
      <option value="rss">RSS 2.0</option>
      <option value="json">JSON(P)</option>
   </select><br />
   Max Items: <input type="text" name="max" /><br />
   <?php
   exit;
}//end if ! url

$_REQUEST['format'] = $_REQUEST['format'] ? $_REQUEST['format'] : 'xhtml';
$_REQUEST['max'] = $_REQUEST['max'] ? $_REQUEST['max'] : 5;

if(substr($_REQUEST['url'],-5,5) == 'basic') {
   $_REQUEST['url'] = substr($_REQUEST['url'],0,strlen($_REQUEST['url'])-5).'full';
}//end if basic

$xmldata = file_get_contents($_REQUEST['url']);
preg_match('/\/feeds\/(.*?)\//',$_REQUEST['url'],$theid);
$theid = $theid[1];
$obj = new OutlineFromXML($xmldata);
$feed = array();
$feed['title'] = $obj->getField('title')->getField('text');
$feed['description'] = $obj->getField('subtitle')->getField('text');
$feed['link'] = 'http://www.google.com/calendar/embed?src='.urlencode($theid);
$feed['items'] = array();

$nodes = $obj->getField('entry')->getNodes();
foreach($nodes as $event) {

   $starttime = false;
   if($event->getField('gd:when'))
        $starttime = $event->getField('gd:when')->getField('starttime');
   if(!$starttime) continue;

   if(strtotime($starttime) < time()) continue;
   if($event->getField('title')->getField('text') == $_REQUEST['filter']) continue;

   $item = array();
   $item['title'] = $event->getField('title')->getField('text');
   $item['description'] = str_replace("\r",'',str_replace("\n",'<br />',$event->getField('content')->getField('text')));
   $item['link'] = $event->getNode(0)->getField('href');
   $item['pubDate'] = strtotime($starttime);
   $feed['items'][strtotime($starttime)] = $item;

}//end foreach

ksort($feed['items']);
$feed['items'] = array_slice($feed['items'],0,$_REQUEST['max']);

if($_REQUEST['format'] == 'xhtml') {
   header('Content-Type: text/html;charset=utf-8');
   echo '<h1><a href="'.$feed['link'].'">'.$feed['title'].'</a></h1>'."\n";
   echo '<i>'.$feed['description'].'</i><br /><br />'."\n";
   echo '<ol>'."\n";
   foreach($feed['items'] as $item) {
      echo '   <li><a href="'.$item['link'].'">'.$item['title'].'</a> - '.$item['description'].' - <i>'.date('Y-m-d',$item['pubDate']).'</i></li>'."\n";
      $count++;
   }//end foreach
   echo '</ol>'."\n";
   exit;
}//end if xhtml

if($_REQUEST['format'] == 'json') {
   header('Content-Type: text/javascript;charset=utf-8');
   $obj = new Outline($feed);
   if($_REQUEST['callback'])
      echo $_REQUEST['callback'].'(';
   echo $obj->toJSON();
   if($_REQUEST['callback'])
      echo ')';
   exit;
}//enf if json

if($_REQUEST['format'] == 'rss') {
   header('Content-Type: application/xml;charset=utf-8');
   echo std_rss_out($feed);
   exit;
}//enf if json


?>