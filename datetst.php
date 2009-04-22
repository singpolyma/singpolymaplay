<?php

header('Contetn-Type: text/plain');
echo strtotime($_REQUEST['time']);
echo "\n\n";
echo date('r',strtotime($_REQUEST['time']));

?>