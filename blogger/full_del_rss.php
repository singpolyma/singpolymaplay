<?php
//returns a full RSS feed of a user's del.icio.us account

if(!$_REQUEST['usr']) {
   ?>
<div style="padding:20px;">
   <h2>Set up Full del.icio.us RSS</h2>
   <p>Your password will be kept private and confidential.</p>
   <p>You may change the password setting again at any time by using this form.</p>
   <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"><div>
      del.icio.us username: <input type="text" name="usr" /><br />
      del.icio.us password: <input type="password" name="p" /><br />
      <input type="submit" value="Go" />
   </div></form>
<br /><br />
   <h2>Get Setup Feed</h2>
   <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>"><div>
      del.icio.us username: <input type="text" name="usr" /><br />
      tag filter (optional): <input type="text" name="tag" /><br />
      <input type="hidden" name="xn_auth" value="no" />
      <input type="submit" value="Go" />
   </div></form>
</div>
   <?php
   exit;
}//end if ! usr

$item = XN_Query::create('Content')
         ->filter('owner','=')
         ->filter('type','eic','delusr')
         ->filter('title','eic',$_REQUEST['usr']);
$item = $item->execute();
if($item && count($item))
   $item = $item[0];
else
   $item = XN_Content::create('delusr',$_REQUEST['usr']);

$item->isPrivate = true;//ensure privacy


if($_REQUEST['p']) {
   $item->description = str_rot13($_REQUEST['p']);
   $item->save();
   echo '<h2>Setup Successful!</h2>';
   exit;
}//end if p

require_once 'xn-app://xoxotools/std_rss_out.php';
require_once 'xn-app://xoxotools/OutlineClasses/OutlineFromXML.php';

$_REQUEST['usr'] = $item->title;
$_REQUEST['p'] = str_rot13($item->description);
$_REQUEST['tag'] = $_REQUEST['tag'] ? '?tag='.$_REQUEST['tag'] : '';

$apiget = file_get_contents('https://'.$_REQUEST['usr'].':'.$_REQUEST['p'].'@api.del.icio.us/v1/posts/all'.$_REQUEST['tag']);
$apiget = new OutlineFromXML($apiget);

$out = array();
$out['title'] = 'del.icio.us / '.$apiget->getField('user');
$out['description'] = 'RSS feed of ALL posts.';
$out['link'] = 'http://del.icio.us/'.$apiget->getField('user');
$out['dc:creator'] = $apiget->getField('user');
$out['pubDate'] = strtotime($apiget->getField('update'));
$out['items'] = array();

foreach($apiget->getNodes() as $item) {
   $outi = array();
   $outi['title'] = $item->getField('description');
   $outi['description'] = $item->getField('extended');
   $outi['link'] = $item->getField('href');
   $outi['guid'] = $item->getField('hash');
   $outi['dc:creator'] = $out['dc:creator'];
   $outi['comments'] = 'http://del.icio.us/url/'.$item->getField('hash');
   $outi['wfw:commentRss'] = 'http://del.icio.us/rss/url/'.$item->getField('hash');
   $outi['pubDate'] = strtotime($item->getField('time'));
   $outi['category'] = explode(' ',$item->getField('tag'));
   $out['items'][] = $outi;
}//end foreach

header('Content-Type: application/xml;charset=utf-8');
echo std_rss_out($out);

?>