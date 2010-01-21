<?php
if(count($thoughts) == 0)
	{
	print '<p>' . NONE_FOUND_TRY_AGAIN . '</p>';
	}
else
	{
	print '<ul class="thoughts" dir="' . $direction . '">';
	foreach($thoughts as $t)
		{
		$a = $t->author();
		print '<li dir="' . $direction . '">';
		print '<p dir="' . $direction . '">';
		print '<a href="/t/' . $t->id . '">';
		$showthought = (isset($highlight)) ? highlight(htmlspecialchars($t->thought()), $highlight) : htmlspecialchars($t->thought());
		print nl2br($showthought);
		print '</a>';
		print '</p>';
		$showauthor = (isset($highlight)) ? highlight(htmlspecialchars($a->name()), $highlight) : htmlspecialchars($a->name());
		print '<cite dir="' . $direction . '"> <a href="/author/' . $a->id . '">' . $showauthor . '</a></cite>';
		print '</li>';
		}
	print '</ul>';
	}
?>
