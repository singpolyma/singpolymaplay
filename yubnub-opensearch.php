<?php

require_once 'xn-app://xoxotools/OutlineClasses/JSON.php';

function yubnubcmd($cmd) {
   if($cmd{0} == '"')
      return substr($cmd,1,strlen($cmd)-2);
   $curl = curl_init('http://yubnub.org/parser/parse?command='.urlencode($cmd));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
   $rtrn = curl_exec($curl);
   curl_close($curl);
   return $rtrn;
}//end function yubnubcmd

$json = new Services_JSON();
$value = $json->decode(file_get_contents('http://yubscripts.ning.com/yn-commandscrape.php?xn_auth=no&cmd='.urlencode($_REQUEST['cmd'])));

header('Content-type: application/xml;charset=utf-8');

$url = html_entity_decode($value->url);

if(substr($url,0,5) != 'http:' || strstr($url,'{')) {
   $url = 'http://yubnub.org/parser/parse?command='.urlencode($_REQUEST['cmd']).'+{searchTerms}';
} else {
   $url = str_replace('%s','{searchTerms}',$url);
   $url = str_replace('${','{',$url);
}

$method = 'GET';
if(strstr($url,'[post]')) {
   $method = 'POST';
   $url = str_replace('[post]','',$url);
}//end if post

?><?xml version="1.0" encoding="UTF-8"?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
   <ShortName><?php echo htmlspecialchars($value->name); ?></ShortName>
   <Description><?php echo htmlspecialchars($value->description); ?></Description>

   <Url xmlns:parameters="http://a9.com/-/spec/opensearch/extensions/parameters/1.0/"
      type="text/html"
      template="<?php echo htmlspecialchars($url); ?>"
      parameters:method="<?php echo htmlspecialchars($method); ?>"
   />

</OpenSearchDescription>