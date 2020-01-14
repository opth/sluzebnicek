<?php 
$db = new SQLite3('./data.sqlite');
$errorMessage = null;
$predmety = ["ČJ","MA","AJ","WA","SV","PG","FY","CT","OZ","EN","IN","OS","PX","TV"];

$g = $_GET;

function vyber_podle_id($id) {
  global $db;

  $stmt = $db->prepare('SELECT id, predmet, popis, datum_odevzdani, datum_zadani, typ_zaznamu, skupina FROM ukoly WHERE id=?');
  $stmt->bindValue(1, $id);
  
  if ($row = $stmt->execute()) {
    return $row->fetchArray();
  } else {
    return false;
  }
}

function zmen_zaznam($newTask, $id, $mam_to_smazat) {
  global $db;
  
  if ($mam_to_smazat) {
    $stmt = $db->prepare('DELETE FROM ukoly WHERE (id,predmet,datum_odevzdani,datum_zadani,typ_zaznamu,skupina)=(:id,:predmet,:datum_odevzdani,:datum_zadani,:typ_zaznamu,:skupina)');
  } else {
    $stmt = $db->prepare('UPDATE ukoly SET (predmet,popis,datum_odevzdani,datum_zadani,typ_zaznamu,skupina)=(:predmet,:popis,:datum_odevzdani,:datum_zadani,:typ_zaznamu,:skupina) WHERE id=:id');
  }

  $skupiny = array();
  if (@$newTask['skupina1'] == "on") array_push($skupiny, 1);
  if (@$newTask['skupina2'] == "on") array_push($skupiny, 2);
  $newTask['skupina'] = join($skupiny, ' + ');

  $stmt->bindValue(':id', $id);
  $stmt->bindValue(':predmet', $newTask['predmet']);
  $stmt->bindValue(':popis', $newTask['popis']);
  $stmt->bindValue(':datum_odevzdani', $newTask['datum_odevzdani']);
  $stmt->bindValue(':datum_zadani', $newTask['datum_zadani']);
  $stmt->bindValue(':typ_zaznamu', $newTask['typ_zaznamu']);
  $stmt->bindValue(':skupina', $newTask['skupina']);

  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (array_key_exists('id', $g)) {
    $newTask = vyber_podle_id($_GET['id']);

    require('./php/edit-view.php');
  } else {
    echo "<h1>Error: cybí parametr id</h1>";
  }

  
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $p = $_POST;

  $qstring = $_SERVER['QUERY_STRING'];
  $preId = preg_replace("/id=/", "   ", $qstring);

  if ($p['passwd'] == "abcd") {
    if ($p['popis'] == '') {
      zmen_zaznam($p, $preId, true);
    } else {
      zmen_zaznam($p, $preId, false);
    }

    require('./html/zmeneno-view.html');
  } else {
    $newTask = $p;
    $errorMessage = "neplatné heslo";
    require('./php/pridej-view.php');
  }
  
}
?>