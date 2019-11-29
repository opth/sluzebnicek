<!DOCTYPE html>
<html lang="cs/cz">
  <head>
    <title>Služebníček 2.D</title>
    <link rel="icon" href="favicon.png">
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap" rel="stylesheet">
  </head>
  <body>
    <div id="header">
      <a href="#"><p>Služebníček 2.D</p></a>
      <span class="vl"></span>
      <a href="#"><p>Přehled</p></a>
      <a href="pridej.php"><p>Přidat úkol</p></a>
      <a href="./roz"><p>RoZ</p></a>
    </div>
    <div id="content">
      <fieldset class="sluzba">
        <legend>Službu má</legend>
        <p>
          <?= blackbox_na_sluzby(je_skolni_tyden(), kolik_tydnu_od_zacatku()) ?>
        </p>
      </fieldset>
      <div class="hw">
        <table id="table">
          <tr>
            <th>Předmět</th>
            <th>Popis</th>
            <th>Datum odevzdání</th>
            <th>Datum zadání</th>
            <th>Skupina</th>
            <th>Typ záznamu</th>
          </tr>
          <?= foreach_require(nadchazejici_ukoly(), 'prehled-tr-ukol.php') ?>
        </table>
      </div>
    </div>
    <div id="footer">
     <span>Služebníčel 2.D © 2019 by Ondřej Pithart | ondrap266@gmail.com</span>
     <span id="right">sqlite verze <?php echo verze_sqlite() ?></span>
    </div>
  </body>
</html>

<!-- <script src="app.js"></script> -->
