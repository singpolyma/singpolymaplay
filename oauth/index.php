<?php
require_once("common.inc.php");

session_start();

$key = $_REQUEST['key'] ? $_REQUEST['key'] : $_SESSION['key'];
$secret = $_REQUEST['secret'] ? $_REQUEST['secret'] : $_SESSION['secret'];
$sha1_method = new OAuthSignatureMethod_HMAC_SHA1();
$plaintext_method = new OAuthSignatureMethod_PLAINTEXT();
$consumer = new OAuthConsumer($key, $secret, NULL);

if($_REQUEST['access_endpoint']) $_SESSION['access_endpoint'] = $_REQUEST['access_endpoint'];
if($_REQUEST['api_endpoint']) $_SESSION['api_endpoint'] = $_REQUEST['api_endpoint'];
if($_REQUEST['post_id']) $_SESSION['post_id'] = $_REQUEST['post_id'];
if($_REQUEST['comment']) $_SESSION['comment'] = $_REQUEST['comment'];
$_SESSION['key'] = $key;
$_SESSION['secret'] = $secret;

if($_REQUEST['action'] == 'start') {
	$rtoken = OAuthRequest::from_consumer_and_token($consumer, NULL, 'GET', $_REQUEST['request_endpoint'], array());
	$rtoken->sign_request($sha1_method, $consumer, NULL);
	$curl = curl_init($rtoken);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
	$rtoken = curl_exec($curl);
	curl_close($curl);
	preg_match('/oauth_token=(.*?)&oauth_token_secret=(.*)/', $rtoken, $rtoken);
	$rtoken_secret = $rtoken[2];
	$rtoken = $rtoken[1];
	if(!$rtoken) die('This is a known bug, just go back and try again');
	$_SESSION['rtoken'] = $rtoken;
	$_SESSION['rtoken_secret'] = $rtoken_secret;
	$callback_url = "$base_url/?action=access";
	$auth_url = $_REQUEST['authorize_endpoint'] . "?oauth_token=$rtoken&oauth_callback=".urlencode($callback_url);
	header('Location: '.$auth_url,true,303);
	exit;
}//end if start

if($_REQUEST['action'] == 'access') {
	$rtoken = new OAuthConsumer($_SESSION['rtoken'], $_SESSION['rtoken_secret']);
	$atoken = OAuthRequest::from_consumer_and_token($consumer, $rtoken, 'GET', $_SESSION['access_endpoint'], array());
	$atoken->sign_request($sha1_method, $consumer, $rtoken);
	$curl = curl_init($atoken);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
	$atoken = curl_exec($curl);
	curl_close($curl);
	preg_match('/oauth_token=(.*?)&oauth_token_secret=(.*)/', $atoken, $atoken);
	$atoken = new OAuthConsumer($atoken[1], $atoken[2]);
	$service = OAuthRequest::from_consumer_and_token($consumer, $atoken, 'POST', $_SESSION['api_endpoint'], array('comment_post_ID' => $_SESSION['post_id'], 'comment' => $_SESSION['comment']));
	$service->sign_request($sha1_method, $consumer, $atoken);
	preg_match('/(.*?)\?(.*)/', $service, $service);
	$curl = curl_init($service[1]);
	curl_setopt($curl, CURLOPT_POST,TRUE);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $service[2]);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
	$service = curl_exec($curl);
	curl_close($curl);
	if(!$service) echo '<b>Comment successful!</b>';
		else echo $service;
	exit;
}//end if access

?>
<html>
<head>
<title>OAuth Wordpress Post Comment</title>
<style type="text/css">
html,body {background-color: white;}
</style>
</head>
<body>
<h1>OAuth Test Client</h1>
<h2>Instructions for Use</h2>
<p>This is a test client that will let you test your OAuth server code. Enter the appropriate information below to test.</p>
<p>Note: we don't store any of the information you type in.</p>

<form method="post" action="">
<h3>Enter The Endpoints</h3>
Request token endpoint: <input type="text" name="request_endpoint" value="<?php echo $_REQUEST['request_endpoint']; ?>" /><br />
Authorize token endpoint: <input type="text" name="authorize_endpoint" value="<?php echo $_REQUEST['authorize_endpoint']; ?>" /><br />
Access token endpoint: <input type="text" name="access_endpoint" value="<?php echo $_REQUEST['access_endpoint']; ?>" /><br />
API endpoint: <input type="text" name="api_endpoint" value="<?php echo $_REQUEST['api_endpoint']; ?>" /><br />
Post ID: <input type="text" name="post_id" value="<?php echo $_REQUEST['post_id']; ?>" /><br />
Comment:<br />
	<textarea name="comment"></textarea>
<h3>Enter Your Consumer Key / Secret</h3>
consumer key: <input type="text" name="key" value="<?php echo $key; ?>" /><br />
consumer secret: <input type="text" name="secret" value="<?php echo $secret;?>" /><br />
<input type="submit" name="action" value="start" />
</body>
</html>
