<h1>Search Google</h1>
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>"><div>
   Query: <input type="text" name="q" />
   <input type="submit" value="Search" />
</div></form>

<?php

if(isset($_REQUEST['q'])) {
   require('google_search.php');
   $results = google_search($_REQUEST['q']);
   echo '<h2>Google Results for '.$_REQUEST['q'].'</h2>'."\n";
   echo '<ul class="xoxo">'."\n";
   foreach($results as $item) {
      echo '   <li><a href="'.htmlspecialchars($item['link']).'">'.htmlspecialchars($item['title']).'</a><br />'.$item['description'].'</li>'."\n";
   }//end foreach results[items]
   echo '</ul>'."\n";
}//end if q

?>