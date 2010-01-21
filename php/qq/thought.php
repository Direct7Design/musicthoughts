<?php

# frame the big thought
print '<div id="mainthought" dir="' . $direction . '">';

# the thought itself
print '<h2 class="thought">' . nl2br(htmlspecialchars($t->thought())) . '</h2>';

# the author 
print '<cite>- <a href="/author/' . $a->id . '">' . htmlspecialchars($a->name()) . '</a>';

# author_source or author_url? add it 
$cite2 = '';
if(strlen($a->url()))
	{
	$linkword = (strlen($t->source_url())) ? $t->source_url() : display_url($a->url());
	$cite2 = '<a href="' . htmlspecialchars($a->url()) . '">' . htmlspecialchars($linkword) . '</a>';
	}
elseif(strlen($t->source_url()))
	{
	$cite2 = htmlspecialchars($t->source_url());
	}
if(strlen($cite2))
	{
	print ' ' . FROM . ' ' . $cite2;
	}
print '</cite>';

# contributor info
print '<address>';
$contributed = '<a href="/contributor/' . $c->id . '">' . htmlspecialchars($c->name()) . '</a>';
if(strlen($c->place()))
	{
	$contributed .= ' ' . FROM . ' ' . htmlspecialchars($c->place());
	}
printf(CONTRIBUTED_BY_S, $contributed);
if(LANG != 'en')
	{
	print '<br /><span id="fixtranslation" dir="ltr">Can you <a href="http://lang.pro/fix/1/' . $t->id . '/' . LANG . '">improve this translation</a>?</span>';
	}
print '</address>';

print '</div>';   # end div#mainthought

# related info
print '<div id="related">';
print category_list($t->categories());
print '</div>';
?>
