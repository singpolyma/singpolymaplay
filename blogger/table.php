<?php header('Content-type: text/plain'); ?><table>
<?php

require('equiv.php');

foreach($equiv as $old => $new)
   echo ' <tr><td>'.htmlentities($old).'</td><td>'.htmlentities($new).'</td></tr> ';

?>
</table>