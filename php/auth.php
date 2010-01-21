<?php
require '../../autharray.php';
if(!isset($_SERVER['PHP_AUTH_USER']) || !isset($auth[$_SERVER['PHP_AUTH_USER']]) || $auth[$_SERVER['PHP_AUTH_USER']] != $_SERVER['PHP_AUTH_PW'])
	{
	header('WWW-Authenticate: Basic realm="musicthoughts.com"');
	header('HTTP/1.0 401 Unauthorized');
	print 'Sorry. Needs authentication.';
	die();
	}
$okadmin = $_SERVER['PHP_AUTH_USER'];
?>
