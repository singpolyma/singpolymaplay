<form method="post">
<textarea name="duh"></textarea>
<input type="submit" />
</form>
<?php

if($_REQUEST['duh']) header('Content-type: text/plain');

$blah = split("\n",$_REQUEST['duh']);

foreach($blah as $it) {
   echo trim(preg_replace('/^[\d]+(.*)$/','$1',$it))."\n";
}//end foreach

?>