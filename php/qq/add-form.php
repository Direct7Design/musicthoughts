<h2>Guidelines for new MusicThoughts</h2>

<div id="guidelines-intro">
<p>
This is just a small collection of beautiful, profound, inspiring quotes about music.
</p><p>
Since I pay professional translators by the word, each new quote added to the site costs me about $20.
</p><p>
So new quotes are only added to the site if they meet these guidelines:
</p>

<ul>
<li>It's quoting someone else, not yourself. (It's profound enough to be quoted by others.)</li>
<li>It can be directly applied to making music.</li>
<li>It's inspiring, not just funny or complaining.</li>
<li>It's succinct. Every word is neccessary.</li>
</ul>

<p>
Please don't be offended if a submitted quote is not added to this small collection.
</p><p>
Thank you!
</p>
</div>
<?php
print '<h2>' . ADD_THOUGHT_HEADER . '</h2>';

print '<form action="/add" method="post"><fieldset>';

# TURN THIS TO HIDDEN IF LOGGED IN
print '<label for="v">' . ADD_VERIFY_LABEL . '</label>';
print '<input type="text" id="v" name="v" size="6" value="" />';

print '<label for="thought">' . ADD_THOUGHT_LABEL . '</label>';
print '<textarea id="thought" name="thought" cols="30" rows="4"></textarea>';

print '<label for="author">' . ADD_AUTHOR_LABEL . '</label>';
print '<input type="text" id="author" name="author" size="20" value="" />';

print '<label for="author_source">' . ADD_AUTHOR_SOURCE_LABEL . '</label>';
print '<input type="text" id="author_source" name="author_source" size="20" value="" />';

print '<label for="author_url">' . ADD_AUTHOR_URL_LABEL . '</label>';
print '<input type="text" id="author_url" name="author_url" size="20" value="" />';
print '</fieldset>';

print '<h2>' . ADD_CONTRIBUTOR_HEADER . '</h2>';
print '<fieldset>';

print '<label for="contributor">' . ADD_CONTRIBUTOR_LABEL . '</label>';
print '<input type="text" id="contributor" name="contributor" size="20" value="' . htmlspecialchars($contributor) . '" />';

print '<label for="contributor_email">' . ADD_CONTRIBUTOR_EMAIL_LABEL . '</label>';
print '<input type="text" id="contributor_email" name="contributor_email" size="20" value="' . htmlspecialchars($contributor_email) . '" />';

print '<label for="contributor_url">' . ADD_CONTRIBUTOR_URL_LABEL . '</label>';
print '<input type="text" id="contributor_url" name="contributor_url" size="20" value="' . htmlspecialchars($contributor_url) . '" />';

print '<label for="contributor_place">' . ADD_CONTRIBUTOR_PLACE_LABEL . '</label>';
print '<input type="text" id="contributor_place" name="contributor_place" size="20" value="' . htmlspecialchars($contributor_place) . '" />';

print '<br />';
print '<br />';
print '<input type="hidden" name="lang" value="' . LANG . '" />';
print '<input type="submit" id="addsubmit" name="submit" value="' . SUBMIT . '" />';

print '</fieldset></form>';
?>
