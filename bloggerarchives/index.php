<h1>Blogger Archive Hack Scripts</h1>
<h2>archive_list.php</h2>
http://singpolymaplay.ning.com/bloggerarchives/archive_list.php?xn_auth=no&amp;url=<span style="color:red;">URL to page with archive list</span>&amp;start=<span style="color:red;">code that marks start of archive list</span>&amp;end=<span style="color:red;">code that marks end of archive list</span>&amp;callback=<span style="color:red;">optional JSONP callback</span>
<br /><br />
Fetches URL and parses the code starting with &amp;start and ending with &amp;end as XOXO, pulling out the URLs and names of the archives.  Gets archive counts for each archive (using hAtom or XOXO Blog Format data on archive pages) and returns a JSON(P) object for the data.  &amp;raw for raw JSON data.

<h2>archive_posts.php</h2>
http://singpolymaplay.ning.com/bloggerarchives/archive_posts.php?xn_auth=no&amp;url=<span style="color:red;">URL to archive page</span>
<br /><br />
Fetches archive page URL and tries to extract hAtom or XOXO Blog Format data (ignoring comments) and returns a JSON(P) object of the results.  Optional &amp;callback or &amp;raw parameters apply.  If XOXO code is contained between &lt;!-- START ARCHIVE XOXO --&gt; and &lt;!-- END ARCHIVE XOXO --&gt;, the rest of the page can be safely ignored by the script, so that it does not all have to be well-formed XML.