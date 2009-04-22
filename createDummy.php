<?php

$dummy = XN_Content::create('Dummy',$_REQUEST['title'],$_REQUEST['description']);
$dummy->save();
echo 'Dummy ID# '.$dummy->id.' Created!';

?>