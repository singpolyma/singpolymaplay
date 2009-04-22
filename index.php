<?php
$viewer = XN_Profile::current();
$name = ($viewer->isLoggedIn() ? $viewer->screenName : 'Stranger');
$app = XN_Application::load();
$editFileUrl = 'http://www.ning.com/?view=apps&amp;op=edit&amp;sop=editFile&amp;currentDir=%2f&amp;fileName=index.php&amp;appUrl='.$app->relativeUrl;
?>
<div id="skeleton">
    <?php if ($viewer->isOwner()) { ?>
        <p><a href="<?php echo $editFileUrl; ?>"><img id="editFile" src="edit_button.gif" alt="Edit This Page" border="0" /></a></p>
    <?php } ?>
    <h3>Hello, <?php echo $name; ?>!</h3>
    <?php if ($viewer->isOwner()) { ?>
        <p>Come on in, the code's nice and warm...</p>
    <?php } else { ?>
        <p>This App is still under development.  Please check back later.</p>
    <?php } ?>
</div>
