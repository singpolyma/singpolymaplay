<?php

require_once 'getTidy.php';
require_once 'xn-app://xoxotools/proxy/normalize_url.php';

$_REQUEST['url'] = normalize_url(trim($_REQUEST['url']));
$domain = explode('/',$_REQUEST['url']);
array_pop($domain);
$dir = implode('/',$domain).'/';
$domain = 'http://'.strtolower($domain[2]);

$doc = new DOMDocument();
$doc->preserveWhiteSpace = false;
@$doc->loadHTML(getTidy($_REQUEST['url']));

function doresolve(&$results,$attribute,$domain,$dir) {
   foreach($results as $node) {
      $href = $node->getAttribute($attribute);
      if(preg_match('/^[^:]*:.*$/',$href)) continue;
      if(!$href) {$node->setAttribute($attribute,$_REQUEST['url']);continue;}
      if($href{0} == '/')
         $node->setAttribute($attribute,$domain.$href);
      else
         $node->setAttribute($attribute,$dir.$href);
   }//end foreach results
}//end doresolve

doresolve($doc->getElementsByTagName('a'),'href',$domain,$dir);
doresolve($doc->getElementsByTagName('link'),'href',$domain,$dir);
doresolve($doc->getElementsByTagName('img'),'src',$domain,$dir);
doresolve($doc->getElementsByTagName('script'),'src',$domain,$dir);
doresolve($doc->getElementsByTagName('form'),'action',$domain,$dir);

header('Content-type: application/xhtml+xml;charset=utf-8');
echo str_replace('<?xml version="1.0"??>','',$doc->saveXML());

?>