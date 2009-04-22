<?php

require_once 'XNC/Comment.php';

class CommentThread {

   protected $comment;
   protected $parent;
   protected $private;
   protected $storeparent;
   protected $displaytemplate = null;
   protected $formtemplate = null;

   public function __construct($parent,$private=false,$storeparent=true) {
      $this->comment = new XNC_Comment($parent);
      $this->parent =& $parent;
      $this->private = $private;
      $this->storeparent = $storeparent;
   }//end constructor

   //set the output template
   public function setTemplate($name,$path) {
      $this->comment->setTemplate($name,$path);
      if($name == 'display')
         $this->displaytemplate = $path;
      if($name == 'form')
         $this->formtemplate = $path;
   }// end function setTemplate

   //checks to see if there is a form to process, and if there is, processes it and adds the new comment
   public function processForm() {
      $comment = $this->comment;
      if($_REQUEST['Comment:_parent_id'] && $_REQUEST['Comment:_parent_id'] != $this->parent->id)
         $comment = new XNC_Comment(XN_Content::load(intval($_REQUEST['Comment:_parent_id'])));
      if ($comment->willProcessForm()) {
         $comment->processForm();
         $cnt = XN_Content::load($comment->id);
         if($this->storeparent)
            $cnt->my->set('parentid',$this->parent->id);
         $cnt->isPrivate = $this->private;
         $cnt->save();
      } elseif ($comment->lastError() != XNC_Comment::ERROR_FORM_ABSENT)
         print $comment->lastError();
   }//end function processForm
   
   protected function removeEvilTags($source) {
        // Code by tREXX [www.trexx.ch], "strip_tags", http://ca3.php.net/manual/en/function.strip-tags.php
        // [Jon Aquino 2005-10-28]
        $allowedTags = '<a><br><b><h1><h2><h3><h4><i><img><li><ol><p><strong><table><tr><td><th><u><ul>';
        $source = strip_tags($source, $allowedTags);
        return preg_replace('/<(.*?)>/ie', "'<'.removeEvilAttributes('\\1').'>'", $source);
    }
    protected function removeEvilAttributes($tagSource) {
        $stripAttrib = 'javascript:|onclick|ondblclick|onmousedown|onmouseup|onmouseover|'.
              'onmousemove|onmouseout|onkeypress|onkeydown|onkeyup';
        return stripslashes(preg_replace("/$stripAttrib/i", 'forbidden', $tagSource));
    }

   //output comments and threads belonging to parent
   public function __toString() {
      $rtrn = '';
      if ($this->parent->my->content($this->comment->referenceAttribute,true)) {
         if($this->displaytemplate) {
            $rtrn .= '<div class="comments">'."\n";
            foreach ($this->parent->my->content($this->comment->referenceAttribute,true) as $comment) {
               $rtrn .= new XNC_Comment($comment);
               $rtrn .= new CommentThread($data);//handle threading
            }//end foreach
            $rtrn .= '</div>'."\n";
         } else {
?>
<xn:head>
<script type="text/javascript">
//<![CDATA[
function toggleitem(postid,linkid,newtxt) {
   var whichpost = document.getElementById(postid);
   if (whichpost.style.display != "block") {
      whichpost.style.display = "block";
   } else {
      whichpost.style.display = "none";
   }
   if(linkid) {
         var lnk = document.getElementById(linkid);
         lnk.href = "javascript:toggleitem('"+postid+"','"+linkid+"','"+lnk.innerHTML+"');";
         lnk.innerHTML = newtxt;
   }
}
//]]>
</script>
</xn:head>
<?php
            $rtrn .= '<ul class="xoxo comments">'."\n";
            foreach ($this->parent->my->content($this->comment->referenceAttribute,true) as $data) {
               $rtrn .= '   <li id="c'.$data->id.'">'."\n";
               $rtrn .= '      Posted on <a href="http://'.$_SERVER['HTTP_HOST'].'/view.php?id='.$file->id.'#c'.$data->id.'" title="'.strtotime($data->createdDate).'">'.date('Y-m-d h:i',strtotime($data->createdDate)).'</a>'."\n";
               $rtrn .= '      by <a href="http://browse.ning.com/any/'.$data->contributorName.'/any/any/" class="author user">'.$data->contributorName.'</a>'."\n";
               $rtrn .= '      <dl>'."\n";
               $rtrn .= '         <dt>body</dt>'."\n";
               $rtrn .= '            <dd class="content">'.$this->removeEvilTags(nl2br($data->description)).'</dd>'."\n";
               $rtrn .= '      </dl>'."\n";
               //handle threading
               $subs = new CommentThread($data);
               $rtrn .= '      <a href="javascript:toggleitem(&quot;'.'commentDiv-'.$data->id.'&quot;);">Add Comment Here</a><div style="display:none;" id="commentDiv-'.$data->id.'">'.$subs->buildForm(false,'commentForm-'.$data->id).'</div>';
               $rtrn .= '      <ul>'."\n";
               $rtrn .= $subs->__toString();
               $rtrn .= '      </ul>'."\n";
               //end <li>
               $rtrn .= '   </li>'."\n";
            }//end foreach
            $rtrn .= '</ul>'."\n";
         }//end if-else displaytemplate
      }//end if content
      return $rtrn;
   }//end function __toString
   public function buildDisplay() {return $this->__toString();}
   
   //return the code for an add comment form
   public function buildForm($cocomment=true,$formid='commentForm') {
      if(!XN_Profile::current()->isLoggedIn())
         return '';
      if($this->formtemplate)
         return ($this->comment->buildForm())."\n".($cocomment ? $this->getCoCo($formid) : '');
      $rtrn = '';
      $rtrn .= '<form id="'.$formid.'" method="post" action="?'.$_SERVER['QUERY_STRING'].'">'."\n";
      $rtrn .= '<input type="hidden" name="xnc_comment" value="xnc_comment" />'."\n";
      $rtrn .=  '<input type="hidden" name="Comment:_parent_id" value="'.$this->parent->id.'" />'."\n";
      $rtrn .=  'Comment: <br />'."\n";
      $rtrn .=  '<textarea name="Comment:description" rows="5" cols="50"></textarea><br />'."\n";
      $rtrn .=  '<input type="submit" name="submit" value="Save Comment" class="button"/><br />'."\n";
      $rtrn .= '</form>'."\n";
      if($cocomment)
         $rtrn .= $this->getCoCo($formid);
      return $rtrn;
   }//end function buildForm
   
   private function getCoCo($formid='commentForm',$integrate=false) {
      $rtrn = '';
      if($integrate)
         $rtrn .= '<a href="http://cocomment.com/"><img src="http://cocomment.com/images/cocomment-integrated.gif" alt="coComment Integrated" /></a>'."\n";
      if(!$pagetitle) $pagetitle = XN_Application::load()->name;
      $rtrn .= '<script type="text/javascript">'."\n";
      $rtrn .= '  var blogTool              = "Ning App";'."\n";
      $rtrn .= '  var blogURL               = "http://'.$_SERVER['HTTP_HOST'].'/";'."\n";
      $rtrn .= '  var blogTitle             = "'.addslashes(XN_Application::load()->name).'";'."\n";
      $rtrn .= '  var postURL               = "http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'].'";'."\n";
      $rtrn .= '  var postTitle             = "'.addslashes($this->parent->title).'";'."\n";
      $rtrn .= '  var commentTextFieldName  = "Comment:description";'."\n";
      $rtrn .= '  var commentButtonName     = "submit";'."\n";
      $rtrn .= '  var commentAuthorLoggedIn = true;'."\n";
      $rtrn .= '  var commentAuthor         = "'.XN_Profile::current()->screenName.'";'."\n";
      $rtrn .= '  var commentFormID         = "'.$formid.'";'."\n";
      $rtrn .= '  var cocomment_force       = false;'."\n";
      $rtrn .= '</script>'."\n";
      if($integrate)
         $rtrn .= '<script type="text/javascript" src="http://www.cocomment.com/js/cocomment.js"></script>'."\n";
      return $rtrn;
   }//end function getCoCo
   
}//end class

$dummy = XN_Content::load(821190);
//$dummy = XN_Content::load(821310);
$comment = new CommentThread($dummy);
$comment->processForm();
echo $comment;
echo $comment->buildForm();

?>