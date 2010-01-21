<?php

### KILL
print '<form action="/xt/' . $t->id . '" method="post"><div align="right">';
print ' <a href="/xl">back to list</a> ';
print '<input type="submit" name="action" value="KILL-APOLOGIZE" />';
print '<input type="submit" name="action" value="KILL-DUPE" />';
print '<input type="submit" name="action" value="KILL-SPAM" />';
print '</div></form>';

### THOUGHT
print '<form action="/xt/' . $t->id . '" method="post">';
print '<fieldset><legend>thought ID#' . $t->id . '</legend>';

print '<label for="approved">approved (t or f)</label>';
print '<input type="text" id="approved" name="approved" size="1" value="' . $t->approved() . '" />';

print '<label for="author_id">author_id</label>';
print '<input type="text" id="author_id" name="author_id" size="4" value="' . $t->author_id() . '" />';

print '<label for="contributor_id">contributor_id</label>';
print '<input type="text" id="contributor_id" name="contributor_id" size="4" value="' . $t->contributor_id() . '" />';

print '<label for="created_at">created_at</label>';
print '<input type="text" id="created_at" name="created_at" size="10" value="' . $t->created_at() . '" />';

print '<label for="source_url">source_url (the “_{author}_ from ____”)</label>';
print '<input type="text" id="source_url" name="source_url" size="40" value="' . htmlspecialchars($t->source_url()) . '" />';

print '<label for="as_rand">as_rand (t or f)</label>';
print '<input type="text" id="as_rand" name="as_rand" size="1" value="' . $t->as_rand() . '" />';
print '<input type="submit" name="action" value="change_thought" />';

print '</fieldset></form>';

if($t->approved() == 't')
	{
	print '<p><a href="http://lang.pro/m/remote/1/' . $t->id . '">fix translation at lang.pro</a></p>';
	}

### TRANSLATIONS
foreach($translations as $r)
	{
	print '<form action="/xt/' . $t->id . '" method="post">';
	print '<fieldset><legend>translation ID#' . $r->id . '</legend>';
	print '<label for="lang-' . $r->id . '">lang</label>';
	print '<input type="text" id="lang-' . $r->id . '" name="lang" size="2" value="' . $r->lang() . '" />';
	$rows = ceil(strlen($r->thought()) / 40);
	$rows += substr_count(trim($r->thought()), "\n");
	print '<label for="thought' . $r->id . '">thought</label>';
	print '<textarea id="thought' . $r->id . '" name="thought" cols="70" rows="' . $rows . '" class="small">' . htmlspecialchars($r->thought()) . '</textarea>';
	print '<input type="hidden" name="translation_id" value="' . $r->id . '" />';
	print '<input type="submit" name="action" value="change_translation" />';
	print '</fieldset></form>';
	}

### CATEGORIES
print '<form action="/xt/' . $t->id . '" method="post">';
print '<fieldset><legend>categories</legend>';
print '<table id="catboxes">';
$breakat = 0;
foreach($categories as $c)
	{
	print ($breakat) ? '</td><td>' : '<tr><td>';
	$checked = (in_array($c->id, $category_ids)) ? ' checked="checked"' : '';
	print '<input type="checkbox" name="category[]" id="cat' . $c->id . '" value="' . $c->id . '"' . $checked . ' />';
	print '<label for="cat' . $c->id . '">' . $c->name() . '</label>';
	if($breakat++ == 1)
		{
		print '</td></tr>';
		$breakat = 0;
		}
	}
print '</table>';
print '<input type="submit" name="action" value="change_categories" />';
print '</fieldset></form>';

###  AUTHOR
print '<form action="/xt/' . $t->id . '" method="post">';
print '<fieldset><legend>author id#' . $author->id . '</legend>';
print '<label for="author_name">author.name</label>';
print '<input type="text" id="author_name" name="name" size="20" value="' . htmlspecialchars($author->name()) . '" />';
print '<label for="author_url">author.url</label>';
print '<input type="text" id="author_url" name="url" size="20" value="' . htmlspecialchars($author->url()) . '" />';
print '<input type="hidden" name="author_id" value="' . $author->id . '" />';
print '<input type="submit" name="action" value="change_author" />';
print '</fieldset></form>';

###  CONTRIBUTOR
print '<form action="/xt/' . $t->id . '" method="post">';
print '<fieldset><legend>contributor id#' . $contributor->id . '</legend>';
print '<label for="shared_id">shared_id</label>';
print '<input type="text" id="shared_id" name="shared_id" size="6" value="' . $contributor->shared_id() . '" />';
print '<label for="contributor_name">contributor.name</label>';
print '<input type="text" id="contributor_name" name="name" size="20" value="' . htmlspecialchars($contributor->name()) . '" />';
print '<label for="contributor_email">contributor.email</label>';
print '<input type="text" id="contributor_email" name="email" size="20" value="' . htmlspecialchars($contributor->email()) . '" />';
print '<label for="contributor_url">contributor.url</label>';
print '<input type="text" id="contributor_url" name="url" size="20" value="' . htmlspecialchars($contributor->url()) . '" />';
print '<label for="contributor_place">contributor.place</label>';
print '<input type="text" id="contributor_place" name="place" size="20" value="' . htmlspecialchars($contributor->place()) . '" />';
print '<input type="hidden" name="contributor_id" value="' . $contributor->id . '" />';
print '<input type="submit" name="action" value="change_contributor" />';
print '</fieldset></form>';

### lang.pro
print '<form action="/xt/' . $t->id . '" method="post"><div align="right">';
print '<input type="submit" name="action" value="SEND-TO-LANG.PRO" />';
print '</div></form>';

?>
