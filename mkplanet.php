<?php

   require_once 'xn-app://xoxotools/std_rss_out.php';

   header('Content-Type: application/xml;charset=utf-8');

   $curl = curl_init('http://www.mkplanet.com/community/');
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
   curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.4) Gecko/20060508 Firefox/1.5.0.4');
   curl_setopt($curl,CURLOPT_HTTPHEADER,array('Cookie: user=MjI4OnNpbmdwb2x5bWE6YjhlMzRiZDc3YmFkN2ViYjA5M2FlNzZjZWQ4ZWJlMTM6NTpuZXN0ZWQ6MDotMTowOjE6OjQwOTY%3D'));
   $page = curl_exec($curl);
   curl_close($curl);

   preg_match('/<MARQUEE.*?>(.*?)<\/center><\/a>/',$page,$array);
   preg_match_all('/<a href="(.*?)"STYLE="text-decoration: none"><b> .*? <\/b>/',$array[1],$links);
   $links = $links[1];

   preg_match_all('/<a href=".*?"STYLE="text-decoration: none"><b> (.*?) <\/b>/',$array[1],$titles);
   $titles = $titles[1];

   preg_match_all('/<A HREF="(.*?)"STYLE="text-decoration: none"> (.*?) <\/a> on (.*?)<\/i>/',$array[1],$tmp);
   $profiles = $tmp[1];
   $names = $tmp[2];
   $times = $tmp[3];

   foreach($links as $idx => $link)
      $links[$idx] = 'http://www.mkplanet.com/community/'.html_entity_decode($link);
   foreach($profiles as $idx => $link)
      $profiles[$idx] = 'http://www.mkplanet.com/community/'.html_entity_decode($link);

   foreach($times as $idx => $time)
      $times[$idx] = strtotime($time);

   $feed = array();
   $feed['title'] = 'Recent mkPLANET Forum Posts';
   $feed['link'] = 'http://www.mkplanet.com/community/';
   $feed['items'] = array();

   foreach($titles as $idx => $title) {
      $item = array();
      $item['title'] = 'Post on "'.$titles[$idx].'"';
      $item['link'] = $links[$idx];
      $item['description'] = 'By <a href="'.$profiles[$idx].'">'.$names[$idx].'</a> at '.date('r',$times[$idx]);
      $item['dc:creator'] = $names[$idx];
      $item['pubDate'] = $times[$idx];
      $feed['items'][] = $item;
   }//end foreach

   echo std_rss_out($feed);

?>