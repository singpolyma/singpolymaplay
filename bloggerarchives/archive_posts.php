<?php

require_once 'xn-app://xoxotools/OutlineClasses/OutlineFromXOXO.php';
require_once 'xn-app://xoxotools/OutlineClasses/OutlineFromHATOM.php';

header('Content-Type: text/javascript;charset=utf-8');

$pagedata = file_get_contents($_REQUEST['url']);
preg_match('/<!-- START ARCHIVE XOXO -->[^\f]*?<!-- END ARCHIVE XOXO -->/',$pagedata,$xoxodata);

$xoxodata = $xoxodata[0];
$xoxodata = preg_replace('/<(img|meta|link|hr|br)([^<>]*?)([\/]?)>/i','<$1$2 />', $xoxodata);
$xoxodata = preg_replace('/&([^;]{10})/i','&amp;$1', $xoxodata);

$xoxo = new OutlineFromXOXO($xoxodata,array('classes' => array('xoxo','posts')));
if(!$xoxo->getNumNodes())
   $xoxo = new OutlineFromXOXO($pagedata,array('classes' => array('xoxo','posts')));
if(!$xoxo->getNumNodes())
   $xoxo = new OutlineFromHATOM($pagedata);
if(!$xoxo->getNumNodes())
   $xoxo = new OutlineFromXOXO($pagedata,array('classes' => array()));

if(!$xoxo->getNumNodes())
   die('No valid XOXO data found!');

$tmp = $xoxo->getNode(0);
if($tmp->getField('rel') == 'home')
   $xoxo->unsetNode(0);
$xoxo->reindexNodes();

for($i=0; $i<$xoxo->getNumNodes(); $i++) {
   $node =& $xoxo->getNode($i);
   $node->unsetAllNodes();
}//end for xoxo

if(!isset($_REQUEST['raw']) && !$_REQUEST['callback']) {
   echo 'if(typeof(BloggerArchive) != "object") BloggerArchive = {};'."\n";
   echo 'BloggerArchive.posts = ';
}//end if ! raw && ! callback
if($_REQUEST['callback'])
   echo $_REQUEST['callback'].'(';
echo $xoxo->toJSON('posts',false,false);
if($_REQUEST['callback'])
   echo ')';
if(!isset($_REQUEST['raw']) && !$_REQUEST['callback']) {
   echo ';'."\n";
   echo 'if(BloggerArchive.callbacks && BloggerArchive.callbacks.list) BloggerArchive.callbacks.list(BloggerArchive.list)';
}//end if ! raw && ! callback

?>