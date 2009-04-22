<?php

/* License (BSD)
Copyright (c) 2004, Ryan Lee
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
    * Neither the name of the ryanlee.org nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
/* Modifications (c) 2006 Stephen Paul Weber */

XN_Application::includeFile('xoxotools','/OutlineClasses/OutlineFromXML.php');

$xanga_username   = "";
$xanga_password   = "";
$xanga_comments   = 0; // 1 = allow comments on Xanga post, 0 = no comments
$xanga_premium    = 0; // 1 = accounts is Premium Xanga, 0 = normal (free)
$xanga_authors    = 0; // 1 = show wp author's name as profile data pref
$xanga_title      = 1; // 1 = use Xanga's title parameter; title will show
                       //     above all other parts of post
$xanga_protected  = 0; // 1 = post your WP password protected posts to Xanga
                       //     as a Xanga protected post

function xanga_fetch_login_key() {
	$xanga_login_page = "http://www.xanga.com/signin.aspx";

	$ch = curl_init($xanga_login_page);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$page = curl_exec($ch);
	if (ereg('__VIEWSTATE" value="([^"]*)"', $page, $out)) {
		return $out[1];
	}
}

function xanga_fetch_login_cookie($key) {
	global $xanga_username;
	global $xanga_password;

	$xanga_userparam  = "txtSigninUsername";
	$xanga_passparam  = "txtSigninPassword";
	$xanga_lkeyparam  = "__VIEWSTATE";
	$xanga_login_page = "http://www.xanga.com/signin.aspx";

	$vars  = $xanga_lkeyparam . "=" . urlencode($key) . "&";
	$vars .= $xanga_userparam . "=" . urlencode($xanga_username) . "&";
	$vars .= $xanga_passparam . "=" . urlencode($xanga_password) . "&";
	$vars .= "btnSignin=Sign+In&txtRegisterEmail=&txtRegisterUsername=&txtRegisterPassword1=&txtRegisterPassword2=";

	$ch = curl_init($xanga_login_page);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_COOKIE, "t=1");
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
	curl_setopt($ch, CURLOPT_HEADER, TRUE);

	$page = curl_exec($ch);

	$cookie  = "";
	
	if (ereg('Set-Cookie: (u=[^;]*)', $page, $out)) {
		$cookie .= $out[1] . "; ";
	}

	if (ereg('Set-Cookie: (x=[^;]*)', $page, $out)) {
		$cookie .= $out[1] . "; ";
	}

	if (ereg('Set-Cookie: (y=[^;]*)', $page, $out)) {
		$cookie .= $out[1] . "; ";
	}

	$cookie .= "t=1";

	return $cookie;
}

function xanga_fetch_posting_key($cookie) {
	global $xanga_premium;

	$xanga_post_page  = "http://www.xanga.com/private/xtools/xtoolspremium.aspx";
	if ($xanga_premium == 1) {
		$xanga_post_page = "http://premium.xanga.com/private/xtools/xtoolspremium.aspx?plain=1";
	}

	$ch = curl_init($xanga_post_page);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_COOKIE, $cookie);

	$page = curl_exec($ch);
	$key  = "";
	if (ereg('__VIEWSTATE" value="([^"]*)"', $page, $out)) {
		$key .= $out[1];
	}

	return urlencode($key);
}

// prototypical function for xanga posting actions
function xanga_post_action($action,$title,$content,$link='',$author='',$obloglink='',$oblogtitle='') {
	global $xanga_comments, $xanga_premium, $post;

	$xanga_commentparam = "";
	if ($xanga_comments == 1) {
		$xanga_commentparam = "chkComments=on&";
	}

	if ($action == 'delete') {
		$actionvar = "btnDelete=Delete";
		if (!xanga_map_xanga_id_exists($ID)) {
			return;
		}
		$xangaID = xanga_map_get_xanga_id($ID);
	} elseif ($action == 'edit') {
		$actionvar = "btnSubmit=Submit";
		if (!xanga_map_xanga_id_exists($ID)) {
			return;
		}
		$xangaID = xanga_map_get_xanga_id($ID);
	} elseif ($action == 'post') {
		$actionvar = "btnSubmit=Submit";
		$xangaID = "";
	}

	$cookie = xanga_fetch_login_cookie(xanga_fetch_login_key());
	$key    = xanga_fetch_posting_key($cookie); // already urlencoded
	$uid    = "";

	if (ereg('(u=[0-9]*)', $cookie, $out)) {
		$uid = $out[1];
	}

	$xanga_post_page  = "http://www.xanga.com/private/xtools/xtoolspremium.aspx";
	if ($xanga_premium == 1) {
		$xanga_post_page = "http://premium.xanga.com/private/xtools/xtoolspremium.aspx?plain=1";
	}

	$xanga_lkeyparam  = "__VIEWSTATE";
	$xanga_bodyparam  = "txtPlainText";
	$xanga_titleparam = "txtTitle";
	$xanga_accsparam  = "radAccess";
	// 1 = public access (default)
	// 2 = private access
	// 3 = protected access (triggered only if post is password protected
	//     AND xanga_protected option is 1)
	$xanga_access = "1";

	if ($action == 'delete' || $action == 'edit') {
		$xanga_post_page .= "?uid=" . $xangaID;
	}

	if ($action == 'edit' || $action == 'post') {
		if ($xanga_title != 1) {
			$head = "<h3>" . $title . "</h3>\n";
			$title = "";
		}
		$head .= "<p><em>This entry was ";
                if($link)
                   $head .= "<a href=\"" . $link . "\">";
                $head .= "originally published";
                if($link)
                   $head .= "</a>";
		if ($xanga_authors == 1 && $author) {
			$head .= " by " . $author;
		}
                if($oblogtitle || $obloglink) $head .= ' at ';
                if($obloglink) $head .= '<a href="'.$obloglink.'">';
                if($oblogtitle) $head .= $oblogtitle;
                if($obloglink && !$oblogtitle) $head .= $obloglink;
                if($obloglink) $head .= '</a>';
                $head .= '</em></p>'."\n";
		$foot = "";
		if ($xanga_comments != 1 && $link) {
			$foot = "<p><em><a href=\"" . $link . "#comments\">Leave / read comments</a></em></p>\n";
		}
		$entry = $head . $content . $foot;
	} elseif ($action == 'delete') {
		$entry = "gone";
	}

	$vars  = "__EVENTTARGET=&__EVENTARGUMENT=&";
	$vars .= $xanga_lkeyparam . "=" . $key . "&";
	$vars .= $xanga_bodyparam . "=" . urlencode($entry) . "&";
	$vars .= $xanga_titleparam . "=" . urlencode($title) . "&";
	$vars .= "txtProfImageName=&proftitle1=&proftitle2=&proftitle3=&";
	$vars .= "xztitle1=&xztitle2=&xzasin1=&";
	$vars .= $xanga_commentparam;
	$vars .= $xanga_accsparam . "=" . $xanga_access . "&";
	$vars .= $actionvar;
	$vars .= "&txtUserId=" . $uid;
	$vars .= "&xbgcolor=&txtAcc=0&xbordercolor=&xcontent=&xcopypost=0&";
	$vars .= "xmsgs=-1";

	$ch = curl_init($xanga_post_page);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
	curl_setopt($ch, CURLOPT_HEADER, TRUE);

	$page = curl_exec($ch);
}

if($_REQUEST['url']) {

   $xanga_username   = $_REQUEST['user'];
   $xanga_password   = $_REQUEST['pass'];

   $_REQUEST['data'] = file_get_contents($_REQUEST['url']);
   if(stristr($_REQUEST['data'],'<rss'))
      $struct = new OutlineFromXML($_REQUEST['data'],array('rootel' => 'rss','itemel' => 'channel>item','collapsels' => array('title','description')));
   if(!$struct && stristr($_REQUEST['data'],'<rdf'))
      $struct = new OutlineFromXML($_REQUEST['data'],array('rootel' => 'rdf:RDF','itemel' => 'item','collapsels' => array('title','description')));
   if(!$struct && stristr($_REQUEST['data'],'<feed'))
      $struct = new OutlineFromXML($_REQUEST['data'],array('rootel' => 'feed','itemel' => 'entry','collapsels' => array('title','content','summary')));
   
   if(is_a($struct->getField('channel'),'Outline')) {
      $channel = $struct->getField('channel');
      foreach($channel->getFields() as $name => $val)
         $struct->addField($name,$val);
      $struct->unsetField('channel');
   }//end if channel
   $tmp = $struct->getNode(0);
   if(!$tmp->getField('link')) {
      foreach($struct->getNodes() as $id => $nodes) {
         foreach($nodes->getNodes() as $node) {
            if($node->getField('rel') == 'alternate') {
               $struct->_subnodes[$id]->setField('link',$node->getField('href'));
               break;
            }//end if
         }//end foreach nodes
      }//end foreach stuct
   }//end if ! link

   $blogtitle = $struct->getField('title');
   $bloglink = $struct->getField('link');
   if(is_a($bloglink,'Outline')) {
      foreach($bloglink->getNodes() as $links) {
         if($links->getField('rel') == 'alternate') {
            $bloglink = $links->getField('href');
            break;
         }//end if alternate
      }//end foreach
   }//end if is_a $bloglink

   if(isset($_REQUEST['all'])) {
      $struct = new Outline(array_reverse($struct->toArray()));
      foreach($struct->getNodes() as $item) {
         $author = $item->getField('author');
         $author = is_a($author,'Outline') ? $author->getField('name') : $author;
         if(stristr($author,'noemail') || stristr($author,'noone') || stristr($author,'example.com')) $author = '';
         xanga_post_action('post',$item->getField('title'),($item->getField('description') ? $item->getField('description') : $item->getField('content')),$item->getField('link'),$author,$bloglink,$blogtitle);
      }//end foreach nodes
   } else {
      $item = $struct->getNode(0);
      $author = $item->getField('author');
      $author = is_a($author,'Outline') ? $author->getField('name') : $author;
      if(stristr($author,'noemail') || stristr($author,'noone') || stristr($author,'example.com')) $author = '';
      xanga_post_action('post',$item->getField('title'),($item->getField('description') ? $item->getField('description') : $item->getField('content')),$item->getField('link'),$author,$bloglink,$blogtitle);
   }//end if isset all
   echo '<div><b>Posted to Xanga</b></div>';
   exit;
}//end if url

?>
<h2>Post items from a feed to Xanga</h2>
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>"><div>
   Feed URL: <input type="text" name="url" /><br />
   Xanga Username: <input type="text" name="user" /><br />
   Xanga Password: <input type="password" name="pass" /><br />
   Post all posts? <input type="checkbox" name="all" /><br />
   <input type="submit" value="Post to Xanga" />
</div></form>