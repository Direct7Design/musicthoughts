<?php
$value = (isset($q)) ? $q : '';
print '<form action="/search" method="get">';
print '<label for="q">' . SEARCH_INTRO . '</label>';
print '<input type="text" id="q" name="q" value="' . htmlspecialchars($value) . '" size="10" />';
print '<input type="submit" id="searchsubmit" name="search" value="' . SEARCH . '" />';
print '</form>';
?>
