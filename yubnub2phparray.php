<?php

function checkXML($data) {//returns FALSE if $data is well-formed XML, errorcode otherwise
      $rtrn = 0;
      $theParser = xml_parser_create();
      if(!xml_parse_into_struct($theParser,$data,$vals)) {
         $errorcode = xml_get_error_code($theParser);
         if($errorcode != XML_ERROR_NONE && $errorcode != 27)
            $rtrn = $errorcode;
      }//end if ! parse
      xml_parser_free($theParser);
      return $rtrn;
}//end function checkXML

XN_Application::includeFile('xoxotools','/OutlineClasses/OutlineFromXOXO.php');
XN_Application::includeFile('xoxotools','/OutlineClasses/OutlineFromJSON.php');
XN_Application::includeFile('xoxotools','/OutlineClasses/OutlineFromXML.php');

function yubnub2phparray($data) {
   if(stristr($data,'<ul') || stristr($data,'<ol'))
      $items = new OutlineFromXOXO($data,array('classes' => array()));
   else if(!checkXML($data))
      $items = new OutlineFromXML($data);
   else
      $items = new OutlineFromJSON($data);
   if(!$items->getNumNodes() && count($items->getFields()) == 1)
      return array_values($items->getFields());
   $array = array();
   foreach($items->getNodes() as $item)
      $array[] = $item->getField('text');
   return $array;
}//end function yubnub2phparray

?>