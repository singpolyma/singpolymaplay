<?php

require_once 'xn-app://xoxotools/OutlineClasses/OutlineFromXOXO.php';
require_once 'xn-app://xoxotools/OutlineClasses/OutlineFromHATOM.php';

header('Content-Type: text/javascript;charset=utf-8');

$pagedata = file_get_contents($_REQUEST['url']);
preg_match('/'.preg_quote($_REQUEST['start'],'/').'[^\f]*?'.preg_quote($_REQUEST['end'],'/').'/',$pagedata,$archivelist);
$archivelist = $archivelist[0];
$archivelist = preg_replace('/<(img|meta|link|hr|br)([^<>]*?)([\/]?)>/i','<$1$2 />', $archivelist);
$archivelist = preg_replace('/&([^;]{10})/i','&amp;$1', $archivelist);
$xoxo = new OutlineFromXOXO($archivelist,array('classes' => array()));

for($i=0; $i<$xoxo->getNumNodes(); $i++) {
   $node =& $xoxo->getNode($i);
   $archivepage = file_get_contents($node->getField('href'));
   preg_match('/<!-- START ARCHIVE XOXO -->[^\f]*?<!-- END ARCHIVE XOXO -->/',$archivepage,$xoxodata);
   $xoxodata = $xoxodata[0];
   if($xoxodata) $archivepage = $xoxodata;
   $archivestruct = new OutlineFromXOXO($archivepage,array('classes' => array('xoxo','posts')));
   if(!$archivestruct->getNumNodes())
      $archivestruct = new OutlineFromHATOM($archivepage);
   if(!$archivestruct->getNumNodes())
      $archivestruct = new OutlineFromXOXO($archivepage,array('classes' => array()));
   $tmp = $archivestruct->getNode(0);
   if($tmp &&is_a($tmp,'Outline') && $tmp->getField('rel') == 'home')
      $archivestruct->unsetNode(0);
   $node->setField('count',$archivestruct->getNumNodes());
}//end for getNumNodes

if(!isset($_REQUEST['raw']) && !$_REQUEST['callback']) {
   echo 'if(typeof(BloggerArchive) != "object") BloggerArchive = {};'."\n";
   echo 'BloggerArchive.list = ';
}//end if ! raw && ! callback
if($_REQUEST['callback'])
   echo $_REQUEST['callback'].'(';
echo $xoxo->toJSON('BloggerArchive',false,false);
if($_REQUEST['callback'])
   echo ')';
if(!isset($_REQUEST['raw']) && !$_REQUEST['callback']) {
   echo ';'."\n";
   echo 'if(BloggerArchive.callbacks && BloggerArchive.callbacks.list) BloggerArchive.callbacks.list(BloggerArchive.list)';
}//end if ! raw && ! callback

?>