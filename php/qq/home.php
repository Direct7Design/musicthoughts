<?php
print category_list($catlist);

print '<h3 id="headread"><a href="/t">' . READ_THOUGHT . '</a></h3>';
print '<h3 id="headadd"><a href="/add">' . ADD_THOUGHT . '</a></h3>';
printf('<h3 id="headauthors"><a href="/author">' . TOP_S . '</a></h3>', AUTHORS);
printf('<h3 id="headcontributors"><a href="/contributor">' . TOP_S . '</a></h3>', CONTRIBUTORS);
print '<h3 id="heademail"><a href="http://launch.groups.yahoo.com/group/musicthoughts/">' . EMAIL_LIST . '</a></h3>';
?>
