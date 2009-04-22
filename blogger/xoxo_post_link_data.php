<?php

require_once 'xn-app://xoxotools/OutlineClasses/OutlineFromXOXO.php';//include XOXO and hAtom code
require_once 'xn-app://xoxotools/OutlineClasses/OutlineFromHATOM.php';

header('Content-Type: text/javascript;charset=utf-8');//output type for JSON(P)

$pagedata = file_get_contents($_REQUEST['url']);//get data
preg_match('/<!-- START ARCHIVE XOXO -->[^\f]*?<!-- END ARCHIVE XOXO -->/',$pagedata,$xoxodata);

$xoxodata = $xoxodata[0];//fix basic XML problems (hardly necessary if comments were used)
$xoxodata = preg_replace('/<(img|meta|link|hr|br)([^<>]*?)([\/]?)>/i','<$1$2 />', $xoxodata);
$xoxodata = preg_replace('/&([^;]{10})/i','&amp;$1', $xoxodata);

$xoxo = new OutlineFromXOXO($xoxodata,array('classes' => array('xoxo','posts')));//pull in XOXO/hAtom
if(!$xoxo->getNumNodes())
   $xoxo = new OutlineFromXOXO($pagedata,array('classes' => array('xoxo','posts')));
if(!$xoxo->getNumNodes())
   $xoxo = new OutlineFromHATOM($pagedata);
if(!$xoxo->getNumNodes())
   $xoxo = new OutlineFromXOXO($pagedata,array('classes' => array()));

if(!$xoxo->getNumNodes())//error if no data
   die('No valid XOXO data found!');

$tmp = $xoxo->getNode(0);//strip rel=home, not needed for this dataset
if($tmp->getField('rel') == 'home')
   $xoxo->unsetNode(0);
$xoxo->reindexNodes();

$final = new Outline();//the outline we're pushing to

for($i=0; $i<$xoxo->getNumNodes(); $i++) {//loop through nodes and get data
   $node =& $xoxo->getNode($i);
   foreach($node->getFields() as $name => $val) {
      $name = explode('#',$name);
      if($name[0] != 'rel') continue;
      $dt = $node->getField('href#'.$name[1]);
      if(in_array('external',explode(' ',$val))) $final->addNode(array('link' => $dt));
   }//end foreach getFields
}//end for xoxo

if(!isset($_REQUEST['raw']) && !$_REQUEST['callback']) {//output JSON(P)
   echo 'if(typeof(BloggerArchive) != "object") BloggerArchive = {};'."\n";
   echo 'BloggerArchive.posts = ';
}//end if ! raw && ! callback
if($_REQUEST['callback'])
   echo $_REQUEST['callback'].'(';
echo $final->toJSON('posts',false,false);
if($_REQUEST['callback'])
   echo ')';
if(!isset($_REQUEST['raw']) && !$_REQUEST['callback']) {
   echo ';'."\n";
   echo 'if(BloggerArchive.callbacks && BloggerArchive.callbacks.list) BloggerArchive.callbacks.list(BloggerArchive.list)';
}//end if ! raw && ! callback

?>