<?php
require_once 'init.php';

$qq = new QQ(Lang::browser_wants());
# $qq->tidy();
$server = $qq->server();

# start qv here, to be passed into templates
$qv = array();
$qv['bodyid'] = basename($_SERVER['SCRIPT_NAME']);
$qv['server'] = $server;

$style_whitelist = array('47', 'levi', 'luke', 'plain', 'none');
$qv['css'] = (isset($_COOKIE['css']) && in_array($_COOKIE['css'], $style_whitelist)) ? $_COOKIE['css'] : '47';

# is this just for navigation?  if not, use Lang
$languages = array('en' => 'English', 'es' => 'Spanish', 'fr' => 'French', 'de' => 'German', 'it' => 'Italian', 'pt' => 'Portuguese', 'ru' => 'Russian', 'ar' => 'Arabic', 'ja' => 'Japanese', 'zh' => 'Chinese');
$qv['languages'] = $languages;

$lang = $server['lang'];
$qv['lang'] = $lang;
$qv['direction'] = Lang::direction($lang);

# HELPERS

# remove http:// and stuff, maybe just show domain
function display_url($url)
	{
	$url = strtolower($url);
	$url = str_replace('http://', '', $url);
	$url = str_replace('www.', '', $url);
	$url = str_replace(strstr($url, '/'), '', $url);  # cut off slash and everything after
	return $url;
	}

# input array of categories, and output HTML
function category_list($cats)
	{
	$html = '<div id="catlist">';
	$html .= '<h4>' . CATEGORIES . '</h4>';
	$html .= '<ul>';
	foreach($cats as $c)
		{
		$html .= '<li><a href="/cat/' . $c->id . '">' . $c->name() . '</a></li>';
		}
	$html .= '</ul>';
	$html .= '</div>';
	return $html;
	}
?>
