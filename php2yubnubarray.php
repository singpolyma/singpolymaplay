<?php

function php2yubnubarray($array,$as='xml',$callback='') {
   $rtrn = '';

   if($as == 'text' || $as == 'txt') {
      header('Content-Type: text/plain;charset=utf-8');
      echo implode("\n",$array);
   }//end if as == text || txt

   if($as == 'xml') {
      header('Content-Type: application/xml;charset=utf-8');
      $rtrn .= '<?xml version="1.0" ?>'."\n";
      $rtrn .= '<items>'."\n";
      foreach($array as $item)
         $rtrn .= '   <item>'.htmlspecialchars(iconv('', 'UTF-8', $item)).'</item>'."\n";
      $rtrn .= '</items>';
   }//end if as == xml

   if($as == 'xoxo') {
      header('Content-Type: application/xml;charset=utf-8');
      $rtrn .= '<?xml version="1.0" ?>'."\n";
      $rtrn .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";
      $rtrn .= '<ul class="xoxo">'."\n";
      foreach($array as $item)
         $rtrn .= '   <li>'.htmlspecialchars(iconv('', 'UTF-8', $item)).'</li>'."\n";
      $rtrn .= '</ul>';
   }//end if as == xoxo

   if($as == 'json') {
      header('Content-Type: text/javascript;charset=utf-8');
      if($callback)
         $rtrn .= $callback.'(';
      $rtrn .= '[';
      foreach($array as $idx => $item) {
         if($idx > 0)
            $rtrn .= ',';
         $rtrn .= '"'.str_replace("\n",'\n',str_replace("\r\n",'\n',addslashes(iconv('', 'UTF-8', $item)))).'"';
      }//end foreach
      $rtrn .= ']';
      if($callback)
         $rtrn .=  ')';
   }//end if as == json

   return $rtrn;
}//end function php2yubnubarray

?>