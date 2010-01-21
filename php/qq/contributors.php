<?php
if(count($contributors) == 0)
	{
	print '<p>' . NONE_FOUND_TRY_AGAIN . '</p>';
	}
else
	{
	print '<ul class="contributors">';
	foreach($contributors as $c)
		{
		print '<li>';
		print '<a href="/contributor/' . $c->id . '">';
		print htmlspecialchars($c->name());
		print '</a>';
		print ' <small>(' . $c->howmany() . ')</small>';
		print '</li>';
		}
	print '</ul>';
	}
?>
