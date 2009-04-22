<?php

error_reporting(0);

$error = false;
function handlerr($errno, $errmsg, $filename, $linenum, $vars) {
   if($errno == 8 || $errno == 2048) return;
   global $error;   
   $error = array('number' => $errno, 'message' => $errmsg);
}//end function handlerr

set_error_handler("handlerr");

header('Content-Type: text/javascript;charset=utf-8');

XN_Application::includeFile('xoxotools','/OutlineClasses/Outline.php');
XN_Application::includeFile('xoxotools','/OutlineClasses/OutlineFromXOXO.php');
require_once 'getBloggerProfile.php';
try {
   $bloggerdata = getBloggerProfile($_REQUEST['url']);
   $bloggerdata = new Outline($bloggerdata);
   if($bloggerdata->getField('contact'))
      $bloggerdata->setField('contact',new OutlineFromXOXO($bloggerdata->getField('contact'),array('classes' => array())));
} catch (Exception $e) {
   $bloggerdata = new Outline(array('error' => array('message' => $e->getMessage())));
}//end try-catch
$bloggerdata->addField('url',$_REQUEST['url']);
if($error)
   $bloggerdata->addField('error',new Outline($error));
if(!isset($_REQUEST['raw']) && !$_REQUEST['callback'])
   echo 'if(typeof(BloggerProfiles) == "undefined") var BloggerProfiles = {};'."\n".'BloggerProfiles.profile = ';
if(!isset($_REQUEST['raw']) && $_REQUEST['callback'])
   echo $_REQUEST['callback'].'(';
echo $bloggerdata->toJSON();
if(!isset($_REQUEST['raw']) && $_REQUEST['callback'])
   echo ')';
if(!isset($_REQUEST['raw']) && !$_REQUEST['callback'])
   echo ';'."\n".'if(BloggerProfiles.callbacks && BloggerProfiles.callbacks.profile) BloggerProfiles.callbacks.profile(BloggerProfiles.profile)';

?>