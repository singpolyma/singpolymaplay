<?php



function getTidy($url) {
//   $curl = curl_init('http://cgi.w3.org/cgi-bin/tidy?docAddr='.urlencode($url).'&forceXML=on');
   $curl = curl_init($url);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
   curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.4) Gecko/20060508 Firefox/2.0');
   $rtrn = curl_exec($curl);
   curl_close($curl);
   $tidy = new tidy;
   $tidy->parseString($rtrn, array('output-xml' => true, 'doctype' => 'loose', 'add-xml-decl' => true),'utf8');
   $tidy->cleanRepair();
   return str_replace('&nbsp;','&#160;',$tidy);
}//end function getTidy

?>