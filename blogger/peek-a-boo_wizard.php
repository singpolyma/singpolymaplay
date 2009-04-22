<div style="padding:2em;">
<?php

if(!$_REQUEST['widget']) {
   ?>
   <h2>Peek-a-boo Widget Wizard for Blogger</h2>
   <form method="get"><div>
      Widget type: <select name="widget"><option value="link">Link List</option> <option value="label">Labels (Tags)</option> <option value="html">(X)HTML / JavaScript</option></select><br />
      Open/Close Handle: <select name="handle"><option value="title">Widget Title</option> <option value="plusminus">[+/-]</option></select><br />
      Hide by default? <input type="checkbox" name="hide" checked="checked" /><br />
      Scrolling? <input type="checkbox" name="scroll" /><br />
      Open links in new window (link list only)? <input type="checkbox" name="newwindow" /><br />
      <input type="submit" value="Generate Code" />
   </div></form>
   </div>
   <?php
   exit;
}//end if submit

if($_REQUEST['widget'] == 'link') :
?>

One note about installation - if you put this code into your template more than once at a time (for multiple lists) you must change the id="ALIST" (bold in the code) to id="BLIST" on the second list, etc.<br />
<br />
&lt;b:widget <b>id='ALIST'</b> locked='false' title='List Title' type='LinkList'&gt;<br />
&lt;b:includable id='main'&gt;<br />
&lt;script type='text/javascript'&gt;<br />
//&lt;![CDATA[<br />
if(typeof(rnd) == 'undefined') var rnd = '';<br />
rnd = Math.floor(Math.random()*1000);<br />
rnd = 'id-' + rnd;<br />
document.write('&lt;a href="#" onclick="tmp = document.getElementById(&amp;quot;' + rnd + '&amp;quot;); tmp.style.display = (tmp.style.display == &amp;quot;none&amp;quot;) ? &amp;quot;block&amp;quot; : &amp;quot;none&amp;quot;; return false;"
<?php if($_REQUEST['handle'] == 'title') : ?>
&gt;');<br />
//]]&gt;<br />
&lt;/script&gt;<br />
&nbsp; &nbsp; &nbsp; &lt;b:if cond='data:title'&gt;&lt;h2&gt;&lt;data:title/&gt;&lt;/h2&gt;&lt;/b:if&gt;<br />
&lt;script type='text/javascript'&gt;<br />
//&lt;![CDATA[<br />
document.write('&lt;\/a&gt;');<br />
//]]&gt;<br />
&lt;/script&gt;<br />
<?php else : ?>
style="float:left;margin-right:5px;"&gt;');<br />
//]]&gt;<br />
&lt;/script&gt;[+/-]<br />
&lt;script type='text/javascript'&gt;<br />
//&lt;![CDATA[<br />
document.write('&lt;\/a&gt;');<br />
//]]&gt;<br />
&lt;/script&gt;<br />
&nbsp; &nbsp; &nbsp; &lt;b:if cond='data:title'&gt;&lt;h2&gt;&lt;data:title/&gt;&lt;/h2&gt;&lt;/b:if&gt;<br />
<?php endif; ?>
&nbsp; &nbsp; &nbsp; &lt;div class='widget-content'&gt;<br />
&lt;script type='text/javascript'&gt;<br />
//&lt;![CDATA[<br />
document.write('&lt;div id="' + rnd + '" style="<?php if($_REQUEST['hide']) echo 'display:none;'; ?><?php if($_REQUEST['scroll']) echo 'height:200px;overflow:auto;'; ?>"&gt;');<br />
//]]&gt;<br />
&lt;/script&gt;<br />
&nbsp; &nbsp; &nbsp; &nbsp; &lt;ul&gt;<br />
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &lt;b:loop values='data:links' var='link'&gt;<br />
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &lt;li&gt;&lt;a <?php if($_REQUEST['newwindow']) echo 'target="_blank" '; ?>expr:href='data:link.target'&gt;&lt;data:link.name/&gt;&lt;/a&gt;&lt;/li&gt;<br />
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &lt;/b:loop&gt;<br />
&nbsp; &nbsp; &nbsp; &nbsp; &lt;/ul&gt;<br />
&lt;script type='text/javascript'&gt;<br />
//&lt;![CDATA[<br />
document.write('&lt;\/div&gt;');<br />
//]]&gt;<br />
&lt;/script&gt;<br />
&nbsp; &nbsp; &nbsp; &nbsp; &lt;b:include name='quickedit'/&gt;<br />
&nbsp; &nbsp; &nbsp; &lt;/div&gt;<br />
&nbsp; &lt;/b:includable&gt;<br />
&lt;/b:widget&gt;<br />

<?php elseif($_REQUEST['widget'] == 'label') : ?>

One note about installation - if you put this code into your template more than once at a time (for multiple lists) you must change the id="ALABEL" (bold in the code) to id="BLABEL" on the second list, etc.<br />
<br />
&lt;b:widget id='<b>ALABEL</b>' locked='false' title='Labels' type='Label'&gt;<br />
&lt;b:includable id='main'&gt;<br />
&lt;script type='text/javascript'&gt;<br />
//&lt;![CDATA[<br />
if(typeof(rnd) == 'undefined') var rnd = '';<br />
rnd = Math.floor(Math.random()*1000);<br />
rnd = 'id-' + rnd;<br />
document.write('&lt;a href="#" onclick="tmp = document.getElementById(&amp;quot;' + rnd + '&amp;quot;); tmp.style.display = (tmp.style.display == &amp;quot;none&amp;quot;) ? &amp;quot;block&amp;quot; : &amp;quot;none&amp;quot;; return false;"
<?php if($_REQUEST['handle'] == 'title') : ?>
&gt;');<br />
//]]&gt;<br />
&lt;/script&gt;<br />
&nbsp; &nbsp; &nbsp; &lt;b:if cond='data:title'&gt;&lt;h2&gt;&lt;data:title/&gt;&lt;/h2&gt;&lt;/b:if&gt;<br />
&lt;script type='text/javascript'&gt;<br />
//&lt;![CDATA[<br />
document.write('&lt;\/a&gt;');<br />
//]]&gt;<br />
&lt;/script&gt;<br />
<?php else : ?>
style="float:left;margin-right:5px;"&gt;');<br />
//]]&gt;<br />
&lt;/script&gt;[+/-]<br />
&lt;script type='text/javascript'&gt;<br />
//&lt;![CDATA[<br />
document.write('&lt;\/a&gt;');<br />
//]]&gt;<br />
&lt;/script&gt;<br />
&nbsp; &nbsp; &nbsp; &lt;b:if cond='data:title'&gt;&lt;h2&gt;&lt;data:title/&gt;&lt;/h2&gt;&lt;/b:if&gt;<br />
<?php endif; ?>
&nbsp; &nbsp; &nbsp; &lt;div class='widget-content'&gt;<br />
&lt;script type='text/javascript'&gt;<br />
//&lt;![CDATA[<br />
document.write('&lt;div id="' + rnd + '" style="<?php if($_REQUEST['hide']) echo 'display:none;'; ?><?php if($_REQUEST['scroll']) echo 'height:200px;overflow:auto;'; ?>"&gt;');<br />
//]]&gt;<br />
&lt;/script&gt;<br />
&lt;ul&gt;<br />
&lt;b:loop values='data:labels' var='label'&gt;<br />
&lt;li&gt;<br />
&lt;b:if cond='data:blog.url == data:label.url'&gt;<br />
&lt;data:label.name/&gt;<br />
&lt;b:else/&gt;<br />
&lt;a expr:href='data:label.url'&gt;&lt;data:label.name/&gt;&lt;/a&gt;<br />
&lt;/b:if&gt;<br />
(&lt;data:label.count/&gt;)<br />
&lt;/li&gt;<br />
&lt;/b:loop&gt;<br />
&lt;/ul&gt;<br />
&lt;script type='text/javascript'&gt;<br />
//&lt;![CDATA[<br />
document.write('&lt;\/div&gt;');<br />
//]]&gt;<br />
&lt;/script&gt;<br />
&nbsp; &nbsp; &nbsp; &nbsp; &lt;b:include name='quickedit'/&gt;<br />
&nbsp; &nbsp; &nbsp; &lt;/div&gt;<br />
&nbsp; &lt;/b:includable&gt;<br />
&lt;/b:widget&gt;<br />

<?php else : ?>

<form method="post" action="http://beta.blogger.com/add-widget">
<input name="infoUrl" value="http://singpolyma-tech.blogspot.com/2006/12/peek-boo-html-widgets.html" type="hidden" />
<input name="widget.title" value="Peek-a-boo HTML" type="hidden" />
<textarea name="widget.content" style="display: none;"> HTML / JavaScript Here </textarea> <textarea name="widget.template" style="display: none;">
&lt;b:includable id='main'&gt;
<?php if($_REQUEST['handle'] == 'title') : ?>&lt;h2 class='title'&gt;<?php endif; ?>
&lt;script type='text/javascript'&gt;
/* &lt;![CDATA[ */
if(typeof(rnd) == 'undefined') var rnd = '';
rnd = Math.floor(Math.random()*1000);
rnd = 'id-' + rnd;
document.write('&lt;a href="#" onclick="tmp = document.getElementById(&amp;quot;' + rnd + '&amp;quot;); tmp.style.display = (tmp.style.display == &amp;quot;none&amp;quot;) ? &amp;quot;block&amp;quot; : &amp;quot;none&amp;quot;; return false;"<?php if($_REQUEST['handle'] == 'title') : ?>&gt;');
/* ]]&gt; */
&lt;/script&gt;
&lt;data:title/&gt;
&lt;script type='text/javascript'&gt;
/* &lt;![CDATA[ */
document.write('&lt;\/a&gt;');
/* ]]&gt; */
&lt;/script&gt;
&lt;/h2&gt;<?php else : ?>style="float:left;margin-right:5px;"&gt;');
/* ]]&gt; */
&lt;/script&gt;[+/-]
&lt;script type='text/javascript'&gt;
/* &lt;![CDATA[ */
document.write('&lt;\/a&gt;');
/* ]]&gt; */
&lt;/script&gt;
&lt;h2 class='title'&gt;&lt;data:title/&gt;&lt;/h2&gt;
<?php endif; ?>
&lt;script type='text/javascript'&gt;
/* &lt;![CDATA[ */
document.write('&lt;div id="' + rnd + '" style="<?php if($_REQUEST['hide']) echo 'display:none;'; ?><?php if($_REQUEST['scroll']) echo 'height:200px;overflow:auto;'; ?>"&gt;');
/* ]]&gt; */
&lt;/script&gt;
&lt;data:content/&gt;
&lt;script type='text/javascript'&gt;
/* &lt;![CDATA[ */
document.write('&lt;\/div&gt;');
/* ]]&gt; */
&lt;/script&gt;
&lt;b:include name='quickedit'/&gt;
&lt;/b:includable&gt;
</textarea>
<input name="go" value="Click Me" type="submit" />
</form>
<?php endif; ?>
</div>