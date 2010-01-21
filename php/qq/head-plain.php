<?php
if(!isset($pagetitle))
	{
	$pagetitle = '';
	}
if(!isset($basehref))
	{
	$basehost = ($server['host'] == 'musicthoughts.dev') ? 'musicthoughts.dev' : 'musicthoughts.com';
	$basehref = 'http://' . $basehost;
	}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $lang; ?>" dir="<?php print $direction; ?>">
<head>
<title><?php print htmlspecialchars(strip_tags($pagetitle)); ?></title>
<meta http-equiv="Content-Type" content="<?php print $server['content_type']; ?>; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="/css/<?php print $css; ?>.css" />
<base href="<?php print $basehref; ?>" />
<?php
if(isset($extraheader))
	{
	print $extraheader;
	}
?>
</head>
<body id="<?php print $bodyid; ?>"<?php if(isset($extrabodytag)) { print ' ' . $extrabodytag; } ?>>
	
<div id="container">
<div id="header">
<div id="navigation">
<h1><a href="/"><?php print MUSICTHOUGHTS; ?></a></h1>
</div>
</div>
<div id="content" dir="<?php print $direction ?>">
<?php
if($flash)
	{
        print '<h1 id="flash">' . $flash . '</h1>';
	}
?>
