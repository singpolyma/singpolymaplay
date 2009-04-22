<div style="padding:2em;">
<?php

if(!$_REQUEST['submit']) {
   ?>
   <h2><a href="http://microformats.org/wiki/hcard">hCard</a> Profile Wizard for Blogger</h2>
   <p>Select what data should be visible in your profile on your blog.  (Some fields may not display on team blogs due to limitations in the code.)</p>
   <form method="get"><div>
      Show photo? <input type="checkbox" name="photo" /><br />
      Show location? <input type="checkbox" name="location" /><br />
      Show "about me"? <input type="checkbox" name="aboutme" /><br />
      <input type="submit" name="submit" value="Generate Code" />
   </div></form>
   </div>
   <?php
   exit;
}//end if ! submit

?>
Insert the following code into the "Edit HTML" section of your blog where you want your profile to display:<br /><br />
<code>
&nbsp;  &lt;b:widget id='profile' locked='no' title='Profile' type='Profile'&gt;<br />
&nbsp; &nbsp; &nbsp; &lt;b:includable id='main' var='profile'&gt;<br />
&nbsp; &nbsp; &nbsp; &lt;b:if cond='data:profile.team'&gt;<br />
&nbsp; &nbsp; &nbsp; &nbsp;  &lt;ul class='xoxo'&gt;<br />
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &lt;b:loop values='data:profile.authors' var='author'&gt;<br />
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &lt;li class='vcard'&gt;&lt;a class='fn url' expr:href='author.userURL'&gt;&lt;data:author.displayname/&gt;&lt;/a&gt;&lt;/li&gt;<br />
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &lt;/b:loop&gt;<br />
&nbsp; &nbsp; &nbsp; &nbsp;  &lt;/ul&gt;<br />
&nbsp; &nbsp; &nbsp; &lt;b:else/&gt;<br />
&nbsp; &nbsp; &nbsp; &nbsp;  &lt;div class='vcard'&gt;<br />
<?php if($_REQUEST['photo']) : ?>
&nbsp; &nbsp; &nbsp; &nbsp;  &lt;b:if cond='data:profile.photo.url'&gt;&lt;img class='photo' style='float:left;margin-right:5px;' expr:src='data:profile.photo.url' expr:width='data:profile.photo.width' expr:height='data:profile.photo.height' expr:alt='data:profile.photo.alt' /&gt;&lt;/b:if&gt;<br />
<?php endif; ?>
&nbsp; &nbsp; &nbsp; &nbsp;  A blog by :<br />
&nbsp; &nbsp; &nbsp; &nbsp;  &lt;a class="fn url" <br />expr:href="data:profile.userUrl"&gt;&lt;data:profile.displayname/&gt;&lt;/a&gt;<br />
<?php if($_REQUEST['location']) : ?>
&nbsp; &nbsp; &nbsp; &nbsp;  &lt;b:if cond='data:profile.location'&gt;&lt;br /&gt; &lt;span class='adr'&gt;&lt;data:profile.location/&gt;&lt;/span&gt;&lt;/b:if&gt;<br />
<?php endif; ?>
<?php if($_REQUEST['aboutme']) : ?>
&nbsp; &nbsp; &nbsp; &nbsp;  &lt;b:if cond='data:profile.aboutme'&gt;&lt;br /&gt;&lt;br /&gt; &lt;span class='note'&gt;&lt;data:profile.aboutme/&gt;&lt;/span&gt;&lt;/b:if&gt;<br />
<?php endif; ?>
&nbsp; &nbsp; &nbsp; &nbsp;  &lt;/div&gt;<br />
&nbsp; &nbsp; &nbsp; &lt;/b:if&gt;<br />
&nbsp; &nbsp; &nbsp; &lt;b:include name='quickedit'/&gt;<br />
&nbsp; &nbsp; &nbsp; &lt;/b:includable&gt;<br />
&nbsp;  &lt;/b:widget&gt;</code>
</div>