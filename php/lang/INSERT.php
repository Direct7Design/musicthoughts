<?php
require 'en.php';
$o = file_get_contents('en.php');

/* CREATE TABLE originals (
        id serial primary key,
        project_id integer REFERENCES projects(id),
        remote_id varchar(127),
        created_at timestamp with time zone not null default CURRENT_TIMESTAMP,
        lang char(2) not null,
        original text,
        context_url text,
        comment text,
        UNIQUE (project_id, remote_id)
);

CREATE TABLE translations (
        id serial primary key,
        original_id integer REFERENCES originals(id) not null,
        translator_id integer not null REFERENCES translators(id),
        lang char(2) not null,
        translation text,
        finished_at timestamp with time zone
);
*/

# INSERT originals
#print "BEGIN;\n";
foreach($words as $k => $v)
	{
	$comment = 'NULL';
	if(preg_match("/'$k'.*\s#(.*)/", $o, $matches))
		{
		$comment = "'" . pg_escape_string(trim($matches[1])) . "'";
		print "UPDATE originals SET comment=$comment WHERE project_id=1 AND remote_id='$k';\n";
		}
	#print "INSERT INTO originals (project_id, remote_id, lang, original, context_url, comment) VALUES (1, '$k', 'en', '" . pg_escape_string($v) . "', 'http://musicthoughts.com', $comment);\n";
	}
#print "COMMIT;\n";

$translators = array('ar' => 9, 'fr' => 2, 'de' => 3, 'it' => 4, 'ja' => 7, 'pt' => 5, 'ru' => 6, 'es' => 1, 'zh' => 8);
foreach($translators as $lang => $translator_id)
	{
	#print "\nBEGIN;\n";
	foreach($words as $k => $v)
		{
		#print "INSERT INTO translations (original_id, translator_id, lang) VALUES ";
		#print "((SELECT id FROM originals WHERE project_id=1 AND remote_id='$k'), $translator_id, '$lang');\n";
		}
	#print "COMMIT;\n";
	}
?>
