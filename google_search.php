<?php

require_once 'xn-app://xoxotools/std_feed_parse.php';

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

function google_search($q) {
   $rtrn = std_feed_parse(yubnubcmd('cmd2rss g '.$q));
   foreach($rtrn['items'] as $id => $item) {
      $rtrn['items'][$id]['description'] = preg_replace('/<a[^\f]*?<\/a>/','',$item['description']);
      $rtrn['items'][$id]['description'] = strip_tags($rtrn['items'][$id]['description']);
      if($rtrn['items'][$id]['description'] == $rtrn['items'][$id]['title'])
         $rtrn['items'][$id]['description'] = '';
      else
         $rtrn['items'][$id]['description'] = substr($rtrn['items'][$id]['description'],0,strlen($rtrn['items'][$id]['description'])-6);
      $rtrn['items'][$id]['title'] = html_entity_decode($item['title']);
   }//end foreach
   return $rtrn['items'];
}//end function google_search

?>