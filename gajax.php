<xn:head>
   <title>Google AJAX</title>
   <script type="text/javascript" src="http://jscripts.ning.com/get.php?xn_auth=no&amp;id=1051526"></script>
   <style type="text/css">
      #userContent {margin-left:10px;margin-top:20px;}
      #userHeader {display:none;}
   </style>
</xn:head>

<form method="get" action="http://www.google.com/search" onsubmit="gajax_load(this.q.value,&quot;gajax_results&quot;);return false;"><div>
   <b>Google AJAX Search</b><br />
   <input type="text" name="q" />
   <input type="submit" value="Go" />
</div></form>
<div id="gajax_results"></div>
<div style="font-size:8pt;font-family:sans-serif;"><a href="http://singpolymaplay.ning.com/gajax.php">Google AJAX</a> powered by <a href="http://yubnub.org/">YubNub</a></div>

<h2>The Google AJAX Widget</h2>
<div>The Google AJAX Widget allows you to embed Google search in your webpages and keep your readers.
Instead of being taken to google.com the search results are displayed directly in your page as with the above form.
To put this widget on your webpage add the following code:</div>

<div>
<textarea id="code-area" style="margin-top:1em;width:400px;height:200px;" rows="10" cols="10" onclick="this.select();">
&lt;script type="text/javascript" src="http://jscripts.ning.com/get.php?xn_auth=no&amp;amp;id=1051526"&gt; &lt;/script&gt;
&lt;form method="get" action="http://www.google.com/search" onsubmit="gajax_load(this.q.value,&amp;quot;gajax_results&amp;quot;);return false;"&gt;&lt;div&gt;
&nbsp;&nbsp; &lt;b&gt;Google AJAX Search&lt;/b&gt;&lt;br /&gt;
&nbsp;&nbsp; &lt;input type="text" name="q" /&gt;
&nbsp;&nbsp; &lt;input type="submit" value="Go" /&gt;
&lt;/div&gt;&lt;/form&gt;
&lt;div id="gajax_results"&gt;&lt;/div&gt;
&lt;div style="font-size:8pt;font-family:sans-serif;"&gt;&lt;a href="http://singpolymaplay.ning.com/gajax.php"&gt;Google AJAX&lt;/a&gt; powered by &lt;a href="http://yubnub.org/"&gt;YubNub&lt;/a&gt;&lt;/div&gt;
</textarea>
</div>

<div>
You can use this form to make the above a site-search box:<br />
<form action="" onsubmit="var thearea = document.getElementById(&quot;code-area&quot;);thearea.innerHTML = thearea.innerHTML.replace(/gajax_load\(.*this.q.value/,&quot;gajax_load('site:&quot;+this.url.value+&quot; '+this.q.value&quot;);return false;"><div>
   Site URL: <input type="text" name="url" />
   <input type="submit" value="Go" />
</div></form>
</div>