<?php

   require_once 'xn-app://xoxotools/std_rss_out.php';

   header('Content-Type: application/xml;charset=utf-8');

   $curl = curl_init('http://www.mkplanet.com/community/modules.php?name=Forums&file=search&search_author='.urlencode($_REQUEST['user']));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
   curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.4) Gecko/20060508 Firefox/1.5.0.4');
   curl_setopt($curl,CURLOPT_HTTPHEADER,array('Cookie: user=MjI4OnNpbmdwb2x5bWE6YjhlMzRiZDc3YmFkN2ViYjA5M2FlNzZjZWQ4ZWJlMTM6NTpuZXN0ZWQ6MDotMTowOjE6OjQwOTY%3D'));
   $page = curl_exec($curl);
   curl_close($curl);

   preg_match_all('/<a href="(.*?)" class="topictitle">.*?<\/a>/',$page,$links);
   $links = $links[1];

   preg_match_all('/<a href=".*?" class="topictitle">(.*?)<\/a>/',$page,$titles);
   $titles = $titles[1];

   preg_match_all('/<span class="postbody">(.*?)<\/span>/',$page,$descriptions);
   $descriptions = $descriptions[1];

   preg_match_all('/class="postdetails">.*?&nbsp; &nbsp;Posted: (.*?)&nbsp;/',$page,$times);
   $times = $times[1];

   foreach($links as $idx => $link)
      $links[$idx] = 'http://www.mkplanet.com/community/'.html_entity_decode($link);

   foreach($times as $idx => $time)
      $times[$idx] = strtotime($time);

   $feed = array();
   $feed['title'] = 'Recent mkPLANET Forum Posts by '.$_REQUEST['user'];
   $feed['link'] = 'http://www.mkplanet.com/community/modules.php?name=Forums&file=search&search_author='.urlencode($_REQUEST['user']);
   $feed['items'] = array();

   foreach($titles as $idx => $title) {
      $item = array();
      $item['title'] = 'Post on "'.$titles[$idx].'"';
      $item['link'] = $links[$idx];
      $item['description'] = $descriptions[$idx];
      $item['pubDate'] = $times[$idx];
      $feed['items'][] = $item;
   }//end foreach

   echo std_rss_out($feed);

?>