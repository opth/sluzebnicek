<?php

/* tohle nahrazuje templatovaci engines, potrebujeme jenom foreach-like iteraci
   na renderovani radku to tabulky */
function foreach_require($the_array, $snippet_file_name) {
  foreach ($the_array as $item) {
    require($snippet_file_name);
  }
}

$db = new SQLite3('data.sqlite');

require('prehled-controller.php')
?>
