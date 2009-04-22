<?php

require_once 'xn-app://singpolymaplay/getTidy.php';
require_once 'xn-app://xoxotools/proxy/normalize_url.php';
require_once 'php2yubnubarray.php';

$doc = new DOMDocument();
$doc->preserveWhiteSpace = false;
if($_REQUEST['url'])
   $doc->loadHTML(getTidy(normalize_url($_REQUEST['url'])));
else
   $doc->loadHTML($_REQUEST['data']);

$xpath = new DOMXPath($doc);
$results = $xpath->query($_REQUEST['query']);

$final = array();
foreach($results as $node) {
   $newDom = new DOMDocument;
   $newDom->appendChild($newDom->importNode($node,1));
   $final[] = str_replace("<?xml version=\"1.0\"?>\n",'',$newDom->saveXML());
}//end foreach results as node

$_REQUEST['as'] = $_REQUEST['as'] ? $_REQUEST['as'] : 'xml';
echo php2yubnubarray($final,$_REQUEST['as'],$_REQUEST['callback']);

?>