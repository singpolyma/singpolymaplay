<div style="padding:20px;">
<h1>Blogger Calendar Widget Creator</h1>
<?php

if(!$_REQUEST['url']) {
   ?>
<p>Paste your feed URL and blog title into the boxes below.</p>
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>"><div>
   Feed URL: <input type="text" name="url" /><br />
   Blog Title: <input type="text" name="title" /><br />
   <input type="submit" value="Generate" />
</div></form>
</div>
   <?php
   exit;
}//end if ! url

?>

<h2>Insert this code right before the &lt;/head&gt; in your template</h2>

<code>
&lt;script type="text/javascript" src="http://jscripts.ning.com/get.php?xn_auth=no&amp;amp;id=1093361"&gt;&lt;/script&gt;<br />
&lt;script type="text/javascript" src="http://jscripts.ning.com/get.php?xn_auth=no&amp;amp;id=2655847"&gt;&lt;/script&gt;<br />
&lt;link rel="stylesheet" type="text/css" href="http://singpolyma.googlepages.com/lightbox.css" /&gt;
</code>

<h2>Blogger Classic - Insert this code into your sidebar area</h2>
<h2>Blogger BETA - Create a new HTML widget and use this code as the content</h2>

<code>
&lt;a class="lbOn" href="#blog-calendar"&gt;&lt;img src="http://aycu08.webshots.com/image/8367/2005439689067120836_rs.jpg" alt="Calendar" title="Calendar" /&gt; Post Calendar&lt;/a&gt;<br />
&lt;div id="blog-calendar" style="display:none;"&gt;<br />
&lt;iframe scrolling="no" frameborder="0" src="http://30boxes.com/external/widget?url=<?php echo urlencode($_REQUEST['url']); ?>&amp;amp;forceTitle=<?php echo urlencode($_REQUEST['title']); ?>&amp;amp;forceTheme=%2Ftheme%2Ftiny&amp;amp;forceRows=5" style="width:100%;height:380px;border-width:0px;"&gt;&lt;/iframe&gt;<br />
&lt;div style="text-align:right;"&gt;&lt;a href="#" class="lbAction" rel="deactivate"&gt;&lt;img src="http://www.ning.com/xnstatic/icn/cross.gif" style="display:inline;width:10px;height:10px;" alt="" /&gt; Close&lt;/a&gt;&lt;/div&gt;<br />
&lt;/div&gt;
</code>

<h2>Credits</h2>
<p>Icons from <a href="http://www.famfamfam.com/lab/icons/">famfamfam</a> and <a href="http://www.ning.com/">Ning</a>.  Some code from <a href="http://particletree.com/features/lightbox-gone-wild">Lightbox Gone Wild</a>.  <a href="http://30boxes.com/">30Boxes</a> generating calendars.  All else by <a href="http://singpolyma-tech.blogspot.com/">Stephen Paul Weber</a>.</p>
</div>