<?php
require 'init.php';

# assuming this script is running in php directory of website. if moved, change this!
$webpath = str_replace('/php', '/web', dirname(__FILE__));
$outfile = $webpath . '/sitemap.xml';
$base = 'http://musicthoughts.com/';

function url($loc, $freq=false, $priority=false)
	{
	$x = "\t<url>\n";
	$x .= "\t\t<loc>$loc</loc>\n";
	if($freq)
		{
		$x .= "\t\t<changefreq>$freq</changefreq>\n";
		}
	if($priority)
		{
		$x .= "\t\t<priority>$priority</priority>\n";
		}
	$x .= "\t</url>\n";
	return $x;
	}

$fp = fopen($outfile, 'w');
fwrite($fp, '<?xml version="1.0" encoding="UTF-8"?>' . "\n");
fwrite($fp, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n");

fwrite($fp, url($base, 'monthly'));

# weekly: categories
foreach(Category::all() as $c)
	{
	fwrite($fp, url($base . 'cat/' . $c->id, 'weekly'));
	}

# monthly: tops
fwrite($fp, url($base . 'author', 'monthly'));
fwrite($fp, url($base . 'contributor', 'monthly'));

# monthly: authors & contributors
foreach(Author::all() as $a)
	{
	fwrite($fp, url($base . 'author/' . $a->id, 'monthly', '0.8'));
	}
foreach(Contributor::all() as $c)
	{
	fwrite($fp, url($base . 'contributor/' . $c->id, 'monthly', '0.2'));
	}

# never : thoughts
foreach(Thought::all('en') as $t)
	{
	fwrite($fp, url($base . 't/' . $t->id, 'never', '1.0'));
	}

fwrite($fp, '</urlset>');
fclose($fp);
?>
