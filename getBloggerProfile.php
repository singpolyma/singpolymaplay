<?php

require_once 'getTidy.php';

function getBloggerProfile($url) {

$bloggerdata = getTidy($url);
$theParser = xml_parser_create();
if(!xml_parse_into_struct($theParser,$bloggerdata,$vals)) {
         $errorcode = xml_get_error_code($theParser);
         if($errorcode != XML_ERROR_NONE && $errorcode != 27)
            $error = array('number' => $errorcode, 'message' => xml_error_string($errorcode));
}//end if ! parse
xml_parser_free($theParser);
$flattento = false;
$flattentag = '';
$flattendat = '';
$subflatten = -1;
$doblogs = false;
$bloggerdata = array();
foreach($vals as $el) {
         $isopen = ($el['type'] == 'open' || $el['type'] == 'complete');//for readability
         $isclose = ($el['type'] == 'close' || $el['type'] == 'complete');
         if($flattento) {//if flattening tags
            if($isopen && $flattentag == $el['tag']) {$subflatten++;}
            if($isclose && $flattentag == $el['tag']) {
               if($subflatten) {
                  $subflatten--;
               } else {
                  if($flattento == 'aboutme') $bloggerdata['aboutme'] = $flattendat.'</p>';
                  if($flattento == 'contact') $bloggerdata['contact'] = $flattendat.'</ul>';
                  $flattendat = '';
                  $flattentag = '';
                  $subflatten = -1;
                  $flattento = '';
                  continue;
               }//end if-else subflatten
            }//end if isclose &&
            $emptytag = false;//assume not an empty tag
            if($isopen) {//if opening tag
               $flattendat .= '<'.strtolower($el['tag']);//add open tag
               if($el['attributes']) {//if attributes
                  foreach($el['attributes'] as $id => $val) {//loop through and add
                     $flattendat .= ' '.strtolower($id).'="'.htmlspecialchars($val).'"';
                   }//end foreach
               }//end if attributes
               $emptytag = ($el['type'] == 'complete' && !$el['value']);//is emptytag?
               $flattendat .= $emptytag?' />':'>';//end tag
               if($el['value']) {$flattendat .= htmlspecialchars($el['value']);}//add contents, if any
            }//end if isopen
            if($el['type'] == 'cdata') {//if cdata
               $flattendat .= htmlspecialchars($el['value']);//add data
            }//end if cdata
            if($isclose) {//if closing tag
               if(!$emptytag) {$flattendat .= '</'.strtolower($el['tag']).'>';}//if not emptytag, write out end tag
            }//end if isclose
            continue;
         }//end if flattento

   if($el['attributes']['ID'] == 'blogs') {$doblogs = true;}
   if($doblogs && $el['tag'] == 'A') {
      if(substr($el['attributes']['HREF'],0,5) == 'http:')
         $bloggerdata['blogs'][] = array('url' => $el['attributes']['HREF'],'name' => $el['value']);
      else {
         $tmp = array_pop($bloggerdata['blogs']);
         if(!$tmp['members']) $tmp['members'] = array();
         $tmp['members'][] = array('url' => 'http://www.blogger.com'.$el['attributes']['HREF'],'name' => $el['value']);
         $bloggerdata['blogs'][] = $tmp;
      }//end if-else http:
         //$bloggerdata['people'][] = array('url' => 'http://www.blogger.com'.$el['attributes']['HREF'],'name' => $el['value']);
   }//end if doblogs
   if($doblogs && $isclose && $el['tag'] == 'TABLE') {$doblogs = false;}
   if($el['tag'] == 'H1') $bloggerdata['name'] = trim($el['value']);
   if($el['tag'] == 'H2' && $el['value'] == 'About Me') {$flattento = 'aboutme';$flattentag = 'P';}
   if($el['tag'] == 'H2' && $el['value'] == 'Contact') {$flattento = 'contact';$flattentag = 'UL';}
   if($el['attributes']['ALT'] == 'My Photo') {$bloggerdata['photo'] = array();$bloggerdata['photo']['url'] = $el['attributes']['SRC'];$bloggerdata['photo']['width'] = $el['attributes']['WIDTH'];$bloggerdata['photo']['height'] = $el['attributes']['HEIGHT'];}
}//end foreach

if($error)
   $bloggerdata['error'] = $error;
return $bloggerdata;

}//end function getBloggerProfile

?>