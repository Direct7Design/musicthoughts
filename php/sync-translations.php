<?php
require 'init.php';
$lpg = new LangProGateway(1);
# go through all originals
foreach($lpg->originals() as $remote_id => $original)
	{
	# only originals with numbered ids
	if(!preg_match('/^\d*$/', $remote_id)) { continue; }
	# go through all their finished translations
	foreach($lpg->finished_translations($remote_id) as $lang => $translation)
		{
		# load MusicThoughts translation
		$t = ThoughtTranslation::get_by_id_lang($remote_id, $lang);
		print 'http://musicthoughts.com/t/' . $remote_id . "\t" . $lang . "\t";
		# if not in ThoughtTranslations already, insert
		if($t === false)
			{
			$t = new ThoughtTranslation(false);
			$t->add(array('thought_id' => $remote_id, 'lang' => $lang, 'thought' => $translation));
			print "ADDED\n";
			}
		elseif($t->thought() == $translation)
			{
			print " (unchanged)\n";
			}
		else
			{
			$t->set(array('thought' => $translation));
			print "UPDATED\n";
			}
		}
	}
?>
