<?php
require 'init.php';
$lpg = new LangProGateway(1);

# the languages we need
$langs = $lpg->langs();
unset($langs[array_search('en', $langs)]);

# the word keys we need
require 'lang/en.php';
$keys = array_keys($words);

# all = array to hold all translations. key=lang-code value=hash array of all k=>v pairs to be written to file
$all = array();
foreach($langs as $lang)
	{
	$all[$lang] = array();
	}

# load all originals
$originals = $lpg->originals();

foreach($keys as $key)
	{
	$translations = $lpg->finished_translations($key);
	foreach($langs as $lang)
		{
		# use new translations if it's done, 'en' if not
		$all[$lang][$key] = (isset($translations[$lang])) ? $translations[$lang] : $words[$key];
		}
	}

# write to files
foreach($all as $lang => $pairs)
	{
	$outfile = 'lang/' . $lang . '.php';
	print "writing $outfile\n";
	$fp = fopen($outfile, 'w');
	fwrite($fp, '<?php' . "\n" . '$words = array(' . "\n");
	foreach($pairs as $k => $v)
		{
		fwrite($fp, "'$k' => '" . addslashes($v) . "',\n");
		}
	fwrite($fp, "'x' => 'x');");
	fclose($fp);
	}
/*
<?php
$words = array(
'HOME' => 'home',
'SEARCH' => 'search',
'MTCATEGORY12' => 'technology');
*/
?>
