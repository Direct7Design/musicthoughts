<?php
require 'init.php';
$t = Get::db('musicthoughts');
$l = Get::db('lang');

$translators = array('es' => 1, 'fr' => 2, 'de' => 3, 'it' => 4, 'pt' => 5, 'ru' => 6, 'ja' => 7, 'zh' => 8, 'ar' => 9);

$t->query("SELECT thought_id, thought FROM thought_translations ORDER BY thought_id ASC");
while($x = $t->next_record())
	{
	# add original & get id
	$original_id = $l->insert('originals', array('project_id' => 1, 'remote_id' => $x['thought_id'], 'lang' => 'en', 'original' => $x['thought'], 'context_url' => 'http://musicthoughts.com/t/' . $x['thought_id']));
	# add null for each translator
	foreach($translators as $lang => $translator_id)
		{
		$l->insert('translations', array('original_id' => $original_id, 'translator_id' => $translator_id, 'lang' => $lang));
		}
	}
?>
