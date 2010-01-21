<h2>Choose a style</h2>
<form action="/style" method="post"><fieldset>
<?php
foreach($style_whitelist as $x)
	{
	print '<input type="submit" name="css" value="' . $x . '" />';
	}
?>
</fieldset></form>
