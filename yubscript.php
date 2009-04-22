<?php

function yubnubcmd($cmd,$geturl=false) {
   if($geturl)
      $curl = curl_init('http://yubnub.org/parser/parse?command=url%20'.urlencode($cmd));
   else
      $curl = curl_init('http://yubnub.org/parser/parse?command='.urlencode($cmd));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
   $rtrn = curl_exec($curl);
   curl_close($curl);
   return $rtrn;
}//end function yubnubcmd

function process_script($shebang, $code) {
   $code = str_replace("\r\n","\n",$code);
   if(substr($shebang,-1,1) == '#') $byline = true;
   $shebang = explode('#',$shebang);
   $postvar = $shebang[1];
   $shebang = $shebang[0];
   if(substr($shebang,0,7) == 'yubnub:') {
      $shebang = str_replace('yubnub:','',str_replace('yubnub://','',$shebang));
      $shebang = yubnubcmd($shebang,true);
   }//end if yubnub:
   $rtrn = '';
   if($byline) $code = explode("\n",$code);
   else $code = array($code);
   foreach($code as $line) {
      if(!$line) continue;
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
      if($postvar) {
         curl_setopt($curl, CURLOPT_URL, $shebang);
         curl_setopt($curl, CURLOPT_POST, true);
         curl_setopt($curl, CURLOPT_POSTFIELDS, $postvar.'='.urlencode($line));
      } else curl_setopt($curl, CURLOPT_URL, $shebang.urlencode($line));
      $rtrn .= "\n".curl_exec($curl);
      curl_close($curl);
   }//end foreach code
   return $rtrn;
}//end process_script

if($_REQUEST['code']) {

   $_REQUEST['code'] = str_replace('${%s}',$_REQUEST['%s'],$_REQUEST['code']);//sub in argument

   $_REQUEST['code'] = str_replace("\r\n","\n",$_REQUEST['code']);//get shebang line
   $_REQUEST['code'] = explode("\n",$_REQUEST['code']);
   $shebang = substr($_REQUEST['code'][0],2,strlen($_REQUEST['code'][0])-2);
   unset($_REQUEST['code'][0]);
   if(substr($_REQUEST['code'][1],0,2) == '#!') {
      $mime = substr($_REQUEST['code'][1],2,strlen($_REQUEST['code'][1])-2);
      unset($_REQUEST['code'][1]);
   }//end if mime-type line
   $_REQUEST['code'] = implode("\n",$_REQUEST['code']);

   $shebang = explode('|',$shebang);

   $innercode = $_REQUEST['code'];
   foreach($shebang as $line) {
      $innercode = process_script($line,$innercode);
   }//end foreach shebang

   if($mime) header('Content-type: '.$mime);

   echo $innercode;

   exit;

}//end if code

?>

<h2>YubScript</h2>
<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
   <textarea name="code" style="width:400px;height:200px;"></textarea><br />
   Arg: <input type="text" name="%s" /><br />
   <input type="submit" value="Run" />
</form>
<br />
The first line is the shebang line, like in bash scripting.  The format is like this:<br />
<code>#!URL/TO/SCRIPT/PARSER|OPTIONAL/SECOND/PARSER/...</code><br />
YubNub-protocol URLs are allowed.  Appending a # to the end of a URL makes each line run as its own script (evaluating line-by-line).  Appending #text POSTs the script to the endpoint in POST var text instead of appending the script to the end of the URL, which is the default.  For example:<br />
<code>#!yubnub://runphp#php|yubnub://yubnub#</code><br />
<br />
If the second line starts with #! then it gives the MIME-type to output as.<br />
${%s} is still replaced with the command passed in.<br />
The rest of the script is just evaluated by the parser specified.