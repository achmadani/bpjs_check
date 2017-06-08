<?php
define('CONS_ID', 'ID');
define('SECRET_KEY', 'password');
define('API_URL', 'http://api_bpjs/');
define('BPJS_NO', 'to_be_defined/');

date_default_timezone_set('UTC');
$tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));

$signature = hash_hmac('sha256', CONS_ID."&".$tStamp, SECRET_KEY, true);
$encodedSignature = base64_encode($signature);

static $ch = null;
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'Accept:'.'application/json',
	'X-cons-id:'.CONS_ID,
	'X-timestamp:'.$tStamp,
	'X-signature:'.$encodedSignature
));
curl_setopt($ch, CURLOPT_URL, API_URL . BPJS_NO);

$res = curl_exec($ch);
if ($res === false) throw new Exception('Could not get reply: '.curl_error($ch));
$dec = json_decode($res, true);

curl_close($ch);
$ch = null;

//display result
echo "<pre>";
print_r($dec);
echo "</pre>";
?>
