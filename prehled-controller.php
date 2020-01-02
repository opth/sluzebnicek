<?php
/*
TODO:
. vyresit $ted > 52
. zabezpeceni proti spamu?
. skupiny v tabulce
. responzivita
*/

function verze_sqlite() {
  global $db;
  $version = $db->querySingle('SELECT SQLITE_VERSION()');
  return $version;
}

function je_skolni_tyden() {
  $ted = date("Y-m-d");
  if ($ted > "2019-12-21" && $ted < "2020-01-05") {
    //podzimni
    return FALSE;
  } elseif ($ted > "2020-02-10" && $ted < "2020-02-16") {
    //jarni
    return FALSE;
  } else {
    return TRUE;
  }
}

function kolik_tydnu_od_zacatku() {
  $tehdy = strtotime("2019-09-02");
  $ted = strtotime("now");
  $pocetTydnu = floor(($ted - $tehdy) / (86400*7));
  return $pocetTydnu;
}

function blackbox_na_sluzby($skolni_tyden, $tydnu_od_tehdy) {
  global $db;

  $tyd = $tydnu_od_tehdy%15;
  $res = $db->query("SELECT id, skupina, jmeno FROM sluzby WHERE skupina=1 ORDER BY id LIMIT 1 OFFSET '$tyd'");
  $sk1_sluzbic = $res->fetchArray();

  $tyd = $tydnu_od_tehdy%14;
  $res = $db->query("SELECT id, skupina, jmeno FROM sluzby WHERE skupina=2 ORDER BY id LIMIT 1 OFFSET '$tyd'");
  $sk2_sluzbic = $res->fetchArray();

  if ($skolni_tyden) {
    return $sk1_sluzbic['jmeno'] . " a " . $sk2_sluzbic['jmeno'] . "<br><p id='lilp'>od " . date("d.m.Y", strtotime('monday this week')) . "</p>";
  } else {
    return "nikdo";
  }
}

function nadchazejici_ukoly() {
  global $db;

  $ukoly = [];

  $res = $db->query("SELECT id, predmet, popis, datum_odevzdani, datum_zadani, skupina, typ_zaznamu FROM ukoly WHERE datum_odevzdani >= date('now') ORDER BY datum_odevzdani ASC");

  while ($row = $res->fetchArray()) {
    array_push($ukoly, $row);
  }

  foreach ($ukoly as $item) {
    if ($item['skupina'] == 0) {
      $item['skupina'] = "1 a 2";
    } elseif ($item['skupina'] == 1) {
      $item['skupina'] = "1";
    } elseif ($item['skupina'] == 2) {
      $item['skupina'] = "2";
    }
  }

  return $ukoly;
}



require('logger.php');
$logger1 = new Logger();
$logger1->getLog();

require('prehled-view.php')
?>
