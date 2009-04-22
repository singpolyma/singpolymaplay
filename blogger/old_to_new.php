<?php

if(!$_POST['data']) {
   ?>
<div style="padding:20px;">
<h2>Convert a Blogger Classic template to the New Blogger</h2>
<p>This is a best-guess conversion.  It does not force the code to be well-formed (required by the new blogger).</p>
<form enctype="multipart/form-data" method="post"><div>
   Classic Template: <input name="data" type="file" />
   <input type="submit" value="Go" />
</div></form>
</div>
   <?php
   exit;
}//end if ! data

require('equiv.php');

$data = XN_Request::uploadedFileContents($_POST['data']);

foreach($equiv as $old => $new)
   $data = preg_replace('/'.preg_quote($old,'/').'/i',$new,$data);

$data = preg_replace('/<BloggerPreviousItems>[^\f]*?<\/BloggerPreviousItems>/','',$data);
$data = preg_replace('/<BloggerArchives>[^\f]*?<\/BloggerArchives>/',"<b:section class='archive' id='archive".time()."' showaddelement='yes' growth='vertical'><b:widget id='BlogArchive".time()."' locked='false' title='Blog Archive' type='BlogArchive'/></b:section>",$data);

header('Content-type: text/plain;charset=utf-8');

echo $data;

?>