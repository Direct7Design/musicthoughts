<?php
print '<h3>' . count($thoughts) . ' unapproved:</h3>';

print '<ul>';
foreach($thoughts as $t)
	{
	print '<li>';
	print '<a href="/xt/' . $t->id . '">' . $t->id . ' + ' . $t->created_at() . '</a>';
	print '</li>';
	}
print '</ul>';
?>
