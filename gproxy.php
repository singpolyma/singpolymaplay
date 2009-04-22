<?php

header('Content-Type: application/x-shockwave-flash');
echo file_get_contents($_REQUEST['url']);

?>