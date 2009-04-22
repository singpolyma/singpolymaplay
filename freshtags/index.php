<script type="text/javascript" src="http://www.thomasfrank.se/downloadableJS/jsonStringify.js"></script>
<script type="text/javascript">
//<![CDATA[

if( document.all && !document.getElementsByTagName )
  document.getElementsByTagName = function( nodeName )
  {
    if( nodeName == '*' ) return document.all;
    var result = [], rightName = new RegExp( nodeName, 'i' ), i;
    for( i=0; i<document.all.length; i++ )
      if( rightName.test( document.all[i].nodeName ) )
 result.push( document.all[i] );
    return result;
  };
document.getElementsByClassName = function( className, nodeName )
{
  var result = [], tag = nodeName||'*', node, seek, i;
  var rightClass = new RegExp( '(^| )'+ className +'( |$)' );
  seek = document.getElementsByTagName( tag );
  for( i=0; i<seek.length; i++ )
    if( rightClass.test( (node = seek[i]).className ) )
      result.push( seek[i] );
  return result;
};

   var selectedValues = {'type':'posts','source':'del.icio.us','format':'drop'};
   var XHTMLBlock = '';

   function formChange() {
      var both = <?php if(isset($_REQUEST['install'])) echo 'true'; else echo 'false';?>;
      var all = document.getElementsByClassName('canhide');
      for(var i in all)
         all[i].style.display = 'none';
      for(var x in selectedValues) {
         if(typeof(selectedValues[x]) != 'string') continue;
         var fields = document.getElementsByClassName(selectedValues[x].replace(/\./g,''));
         for(var i in fields)
            fields[i].style.display = 'block';
      }//end for in selectedValues
      if(both) {
         fields = document.getElementsByClassName('tags');
         for(var i in fields)
            fields[i].style.display = 'block';
         fields = document.getElementsByClassName('posts');
         for(var i in fields)
            fields[i].style.display = 'block';
      }//end if both
      all = document.getElementsByClassName('forcehide');
      for(var i in all)
         all[i].style.display = 'none';
   }//end function formChange

   function generateCode() {
      var all = document.getElementsByTagName('input');
      for(var i in all) {
         if(all[i].type == 'text') selectedValues[all[i].name] = all[i].value;
      }//end for i in all
      var both = <?php if(isset($_REQUEST['install'])) echo 'true'; else echo 'false';?>;
      if(both) {
         if(document.getElementById('code').firstChild)
            document.getElementById('code').firstChild.nodeValue = ' ';
         else document.getElementById('code').innerText = ' ';
         selectedValues.type = 'tags';
         selectedValues.format = 'drop';
         XHTMLBlock = 'freshtags_tags';
         if(selectedValues.source == 'blogger') {
            selectedValues.source = 'local';
            selectedValues.varname = 'BloggerLabels';
            selectedValues.tag_url = '/search/label/%tags%';
            selectedValues.join_char = '/';
            var tmp = selectedValues.url;
            delete selectedValues.url;
         }//end if source == blogger
         generateMostCode();
         if(selectedValues.source == 'local') {
            selectedValues.source = 'blogger';
            delete selectedValues.varname;
            selectedValues.url = tmp;
            delete selectedValues.tag_url;
            delete selectedValues.join_char;
         }//end if source == blogger
         selectedValues.type = 'posts';
         selectedValues.format = 'list';
         selectedValues.rows = 10;
         selectedValues.tag_list = 'freshtags_tags';
         XHTMLBlock = 'freshtags_posts';
         generateMostCode(true);
         delete selectedValues.rows;
         delete selectedValues.tag_list;
         <?php if(isset($_REQUEST['blogger'])) echo 'bloggerButton();'; ?>
      } else generateMostCode();
   }//end function generateCode

   function generateMostCode(add) {
      if(!XHTMLBlock) {alert('Please fill in an XHTML Block ID!');return;}
      var both = <?php if(isset($_REQUEST['install'])) echo 'true'; else echo 'false';?>;
      for(var x in selectedValues)
         if(!selectedValues[x]) delete selectedValues[x];
      if(selectedValues.format != 'drop' && selectedValues.format != 'drop-add') delete selectedValues.prompt;
      var json = JSONstring.make(selectedValues);
      if(!add) {
         var code = '';
      } else {
         if(document.getElementById('code').firstChild)
            var code = document.getElementById('code').firstChild.nodeValue;
         else var code = document.getElementById('code').innerText;
      }//end if overrideboth > 1
      if(both && !add)
         code += '<!-- FreshTags0.5-Singpolyma2 -->\n';
      if(!add)
         code += "<script type=\"text/javascript\">\n\nif(typeof(WidgetData) != 'object') WidgetData = {};\nif(typeof(WidgetData['freshtags']) != 'object') WidgetData['freshtags'] = {};\n\n";
      code += "WidgetData['freshtags']['"+XHTMLBlock+"'] = "
      code += json.replace(/{/g,'{\n').replace(/\n/g,'\n   ').replace(/}/g,'\n}')+';\n\n';
      if(!both || add)
         code += '<\/script>';
      if(both && add)
         code += '\n<script type="text/javascript" src="http://jscripts.ning.com/get.php?xn_auth=no&amp;id=818185"><\/script>\n<div id="freshtags_tags"><i>FreshTags Loading...</i></div>\n<div id="freshtags_posts"></div>\n<a href="http://ghill.customer.netspace.net.au/freshtags/" title="Categories by FreshTags"><img src=" http://ghill.customer.netspace.net.au/freshtags/freshtags-btn.png" alt="FreshTags" /></a>\n<!-- /FreshTags0.5-Singpolyma2 -->';
      if(document.getElementById('code').firstChild) document.getElementById('code').firstChild.nodeValue = code;
      else document.getElementById('code').innerText = code;
      window.location.hash = 'code';
   }//end function generateMostCode

   function bloggerButton() {
      if(document.getElementById('code').firstChild)
         var code = document.getElementById('code').firstChild.nodeValue;
      else var code = document.getElementById('code').innerText;
      var frm = '';
      if(selectedValues.source == 'blogger') frm += '<h3>Add this code to your sidebar</h3><p>(Hint: go to the Edit HTML view and click expand widget templates)</p><br /><code>' + "&lt;b:widget id='LABELJSON' locked='false' title='Labels' type='Label'>\n&lt;b:includable id='main'>\n&lt;script type='text/javascript'>\nBloggerLabels = {&lt;b:loop values='data:labels' var='label'>&quot;&lt;data:label.name/>&quot;:&lt;data:label.count/>,&lt;/b:loop>};\n&lt;\/script>\n&lt;\/b:includable>\n&lt;/b:widget>" + '<\/code>';
      frm += '<form target="_blank" method="post" action="http://beta.blogger.com/add-widget">\n';
      frm += '<input name="infoUrl" value="http://singpolyma-tech.blogspot.com/2006/09/freshtags-singpolyma-2.html" type="hidden" />\n';
      frm += '<input name="widget.title" value="FreshTags" type="hidden" />\n';
      frm += '<textarea name="widget.content" style="display: none;">'+code+'</textarea>';
      frm += '<input type="text" name="widget.template" style="display: none;" value="&lt;h2&gt;&lt;data:title/&gt;&lt;/h2&gt; &lt;data:content/&gt;" />';
      frm += '<input name="go" value="Add FreshTags to your blog" type="submit" />';
      frm += '<\/form>';
      document.getElementById('code').innerHTML = frm;
   }//end function bloggerButton

//]]>
</script>

<h2>FreshTags Generator</h2>
<p>For more information about many of these fields, see the <a href="http://singpolyma-tech.blogspot.com/2006/09/widgetdata.html">WidgetData post</a>.</p>
<fieldset>
   <form method="get" onsubmit="generateCode();return false;"><dl>

      <dt<?php if(isset($_REQUEST['install'])) echo ' style="display:none;"';?>><label for="xhtmlblock">XHTML Block ID</label></dt>
         <dd<?php if(isset($_REQUEST['install'])) echo ' style="display:none;"';?>><input type="text" name="xhtmlblock" id="xhtmlblock" onchange="XHTMLBlock = this.value;" /></dd>

      <dt<?php if(isset($_REQUEST['install'])) echo ' style="display:none;"';?>><label for="type">Widget Type</label></dt>
         <dd<?php if(isset($_REQUEST['install'])) echo ' style="display:none;"';?>><select name="type" id="type" onchange="selectedValues.type = this.value; formChange();">
            <option value="posts" title="Posts / Items">Posts</option>
            <option value="tags" title="Tags / Categories">Tags</option>
            <option value="external" title="External Posts / Items (not loaded on page load)">External</option>
         </select></dd>

      <dt><label for="source">Data Source</label></dt>
         <dd><select name="source" id="source" onchange="selectedValues.source = this.value; formChange();">
            <option class="canhide tags posts external" value="del.icio.us">del.icio.us</option>
            <option class="canhide posts external<?php if(isset($_REQUEST['install'])) echo ' forcehide';?>" value="wordpress">WordPress</option>
            <option class="canhide tags<?php if(isset($_REQUEST['install'])) echo ' forcehide';?>" value="local">Local Variable</option>
            <option class="canhide posts external<?php if(isset($_REQUEST['install'])) echo ' forcehide';?>" value="feed">Feed (RSS or ATOM)</option>
            <option class="canhide tags posts external" value="mediawiki">MediaWiki</option>
            <option class="canhide posts external" value="blogger">Blogger</option>
         </select></dd>

      <dt<?php if(isset($_REQUEST['install'])) echo ' style="display:none;"';?>><label for="format">Format</label></dt>
         <dd<?php if(isset($_REQUEST['install'])) echo ' style="display:none;"';?>><input type="text" name="format" id="format" onchange="selectedValues.format = this.value; formChange();" value="drop" /></dd><dd style="margin-left:-80px;"><small>May be: drop, drop-add, list (etc), cust_html, array (JSON), or JavaScript function</small></dd>
      <dt><label for="rows">Rows / Maxitems (optional)</label></dt>
         <dd><input type="text" name="rows" id="rows" onchange="selectedValues.rows = this.value; formChange();" /></dd>
      <dt><label for="defs">Default Tags (optional)</label></dt>
         <dd><input type="text" name="defs" id="defs" onchange="selectedValues.defs = this.value; formChange();" /></dd>
      <dt><label for="no_autocapture">No Auto-capture</label></dt>
         <dd><input type="checkbox" name="no_autocapture" id="no_autocapture" onchange="selectedValues.no_autocapture = this.checked ? true : false; formChange();" /></dd>

      <dt class="canhide drop drop-add"><label for="prompt">Prompt</label></dt>
         <dd class="canhide drop drop-add"><input type="text" name="prompt" id="prompt" onchange="selectedValues.prompt = this.value; formChange();" value="- Tags -" /></dd>

      <dt class="canhide tags"><label for="tag_url">Tag URL (optional, %tags% becomes tags)</label></dt>
         <dd class="canhide tags"><input type="text" name="tag_url" id="tag_url" onchange="selectedValues.tag_url = this.value; formChange();" /></dd>
      <dt class="canhide tags"><label for="join_char">Tag Join Character</label></dt>
         <dd class="canhide tags"><input type="text" name="join_char" id="join_char" onchange="selectedValues.join_char = this.value; formChange();" value="+" /></dd>

      <dt class="canhide posts external<?php if(isset($_REQUEST['install'])) echo ' forcehide';?>"><label for="tag_list">Tag List ID (optional)</label></dt>
         <dd class="canhide posts external<?php if(isset($_REQUEST['install'])) echo ' forcehide';?>"><input type="text" name="tag_list" id="tag_list" onchange="selectedValues.tag_list = this.value; formChange();" /></dd>
      <dt class="canhide posts external"><label for="feedurl">Feed URL (optional)</label></dt>
         <dd class="canhide posts external"><input type="text" name="feedurl" id="feedurl" onchange="selectedValues.feedurl = this.value; formChange();" /></dd>

      <dt class="canhide delicious"><label for="username">Username</label></dt>
         <dd class="canhide delicious"><input type="text" name="username" id="username" onchange="selectedValues.username = this.value; formChange();" /></dd>
      <dt class="canhide delicious"><label for="anchor">Anchor Tag (optional)</label></dt>
         <dd class="canhide delicious"><input type="text" name="anchor" id="anchor" onchange="selectedValues.anchor = this.value; formChange();" /></dd>

      <dt class="canhide wordpress blogger"><label for="url">URL</label></dt>
         <dd class="canhide wordpress blogger"><input type="text" name="url" id="url" onchange="selectedValues.url = this.value; formChange();" /></dd>

      <dt class="canhide mediawiki"><label for="mainpage">Mainpage URL</label></dt>
         <dd class="canhide mediawiki"><input type="text" name="mainpage" id="mainpage" onchange="selectedValues.mainpage = this.value; formChange();" /></dd>

      <dt></dt><dd><input type="submit" value="Generate Code" /></dd>

   </dl></form>
</fieldset>
<script type="text/javascript">formChange();</script>

<h2>Code</h2>
<pre id="code" style="font-family:monospace;font-size:14pt;"> </pre>
