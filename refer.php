<?php

header('Content-type: text/javascript');
echo 'document.writeln("'.$_SERVER['HTTP_REFERER'].'");';

?>