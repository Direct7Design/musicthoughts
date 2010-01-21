</div>

<hr />
<div id="footer">
<?php
$t = Thought::random1(LANG);
if(is_a($t, 'Thought'))
	{
	$a = $t->author();
	print htmlspecialchars($t->thought());
	print ' - ';
	print '<a href="/author/' . $a->id . '">' . htmlspecialchars($a->name()) . '</a>';
	}
?>
</div>
</div>
<?php
if($_SERVER['SERVER_NAME'] == 'musicthoughts.com')
	{
?>
<script type="text/javascript">
document.write(unescape("%3Cscript src='http://www.google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-6771194-1");
pageTracker._trackPageview();
} catch(err) {}</script>
<?php
	}
?>
</body>
</html>
