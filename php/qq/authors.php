<?php
if(count($authors) == 0)
	{
	print '<p>' . NONE_FOUND_TRY_AGAIN . '</p>';
	}
else
	{
	print '<ul class="authors">';
	foreach($authors as $a)
		{
		print '<li>';
		print '<a href="/author/' . $a->id . '">';
		print htmlspecialchars($a->name());
		print '</a>';
		print ' <small>(' . $a->howmany() . ')</small>';
		print '</li>';
		}
	print '</ul>';
	}
?>
