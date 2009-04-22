<?php

XN_Application::includeFile('xoxotools','/OutlineClasses/Outline.php');
XN_Application::includeFile('xoxotools','/OutlineClasses/OutlineFromXOXO.php');
XN_Application::includeFile('xoxotools','/OutlineClasses/OutlineFromOPML.php');
XN_Application::includeFile('xoxotools','/OutlineClasses/JSON.php');
$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);

//Reading List
function process_reading_list($obj) {
   foreach($obj->getNodes() as $node) {
      if($node->getField('href'))
         echo '   <li><a href="'.$node->getField('href').'" title="'.$node->getField('title').'">'.$node->getField('text').'</a>'.($node->getField('href#1') ? ' <a href="'.$node->getField('href#1').'" rel="alternate">[feed]</a>' : '').'</li>'."\n";
      if($node->getNumNodes())
         process_reading_list($node);
   }//end foreach nodes
}//end function
$readinglistdata = file_get_contents('http://www.awriterz.org/etcetc/boxtheweb/exportboxes.php?user=hash%3Afc6e6f4412f270b70b869cf7757122d4&format=xoxo&feedsonly=on');
if(stristr('<opml',$readinglistdata))
   $obj = new OutlineFromOPML($readinglistdata);
else
   $obj = new OutlineFromXOXO($readinglistdata);
$obj->toOPMLfields();
$obj->toXOXOfields();
echo '<h2>Reading List</h2>'."\n";
echo '<ul>'."\n";
process_reading_list($obj);
echo '</ul>'."\n";

//Blogger
require_once 'getBloggerProfile.php';
$bloggerdata = getBloggerProfile('http://www.blogger.com/profile/10992009');
echo '<h2>Blogger</h2>';
echo '<h1>'.$bloggerdata['name'].'</h1>'."\n";
echo '<img style="float:right;" src="'.$bloggerdata['photo'].'" alt="" />';
echo '<h3>About Me</h3>'."\n";
echo $bloggerdata['aboutme'];
echo '<h3>Contact</h3>'."\n".$bloggerdata['contact'];
echo '<h3>Blogs</h3>'."\n<ul>\n";
foreach($bloggerdata['blogs'] as $blog)
   echo '   <li><a href="'.$blog['url'].'">'.$blog['name'].'</a></li>'."\n";
echo '</ul>'."\n";
echo '<h3>Related Profiles</h3>'."\n<ul>\n";
foreach($bloggerdata['people'] as $blog)
   echo '<li><a href="'.$blog['url'].'">'.$blog['name'].'</a></li>';
echo '</ul>'."\n";

//Taglag
$taglagdata = file_get_contents('http://tagalag.com/profile.html?email=singpolyma@gmail.com&json=1');
$taglagdata = $json->decode($taglagdata);
//profile_email
echo '<a style="float:right;" href="http://tagalag.com/profile.html?id='.$taglagdata['profile_id'].'">[taglag profile]</a>'."\n";
echo '<h2>Taglag</h2>'."\n";
echo '<h1>'.$taglagdata['profile_display_name'].'</h1>'."\n";

echo '<h3>Tags</h3>'."\n";
echo '<ul>'."\n";
foreach(array_keys($taglagdata['taggers']) as $tag)
   echo '   <li><a href="http://tagalag.com/tag.html?tag='.$tag.'" rel="tag">'.$tag.'</a></li>';
echo '</ul>'."\n";

echo '<h3>Taggers/Taggees</h3>'."\n";
echo '<ul>'."\n";
foreach($taglagdata['display_names'] as $id => $name)
   echo '   <li><a href="http://tagalag.com/profile.html?id='.$id.'">'.$name.'</a></li>'."\n";
echo '</ul>'."\n";

echo '<h3>Feeds</h3>'."\n";
echo '<ul>'."\n";
foreach($taglagdata['feeds'] as $feed)
   echo '   <li><a href="'.$feed[3].'">'.$feed[1].'</a> <a href="'.$feed[2].'">[feed]</a></li>'."\n";
echo '</ul>'."\n";



//Del.icio.us
$deldata = file_get_contents('http://del.icio.us/feeds/json/singpolyma/?count=100&raw');
$deldata = $json->decode($deldata);
echo '<h2>Del.icio.us</h2>'."\n";
echo '<ul>'."\n";
foreach($deldata as $count => $item) {
   if($count > 20) break;
   echo '   <li><a href="'.$item['u'].'" title="'.$item['n'].'">'.$item['d'].'</a>'."\n";
   echo '      <ul>'."\n";
   foreach($item['t'] as $tag)
      echo '         <li><a href="http://del.icio.us/singpolyma/'.$tag.'" rel="tag">'.$tag.'</a></li>'."\n";
   echo '      </ul>'."\n";
   echo'   </li>'."\n";
}//end foreach
echo '</ul>'."\n";

?>