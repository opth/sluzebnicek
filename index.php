<?php

/* tohle nahrazuje templatovaci engines, potrebujeme jenom foreach-like iteraci
   na renderovani radku to tabulky */
function foreach_require($the_array) {
  foreach ($the_array as $item) {
    require('./php/prehled-tr-ukol.php');
  }
}
$db = new SQLite3('./data.sqlite');

require('./php/prehled-controller.php')
?>
