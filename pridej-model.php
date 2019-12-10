<?php
$db = new SQLite3('data.sqlite');

function pridej_do_db2($newTask) {
  global $db;

  $stmt = $db->prepare('INSERT INTO ukoly(predmet, popis, datum_zadani, datum_odevzdani, typ_zaznamu, skupina) VALUES (?,?,?,?,?,?)');

  $html_popis = htmlspecialchars($newTask['popis']);

  $skupiny = array();
  if (@$newTask['skupina1'] == "on") array_push($skupiny, 1);
  if (@$newTask['skupina2'] == "on") array_push($skupiny, 2);
  $newTask['skupina'] = join($skupiny, ' + ');

  $stmt->bindValue(1, $newTask['predmet']);
  $stmt->bindValue(2, $html_popis);
  $stmt->bindValue(3, $newTask['datum_zadani']);
  $stmt->bindValue(4, $newTask['datum_odevzdani']);
  $stmt->bindValue(5, $newTask['typ_zaznamu']);
  $stmt->bindValue(6, $newTask['skupina']);

  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
} 
?>
