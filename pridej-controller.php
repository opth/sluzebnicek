<?php

$errorMessage = null;

$newTask = $_POST;
unset($newTask['passwd']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if ($_POST['passwd'] == "abcd") {
    require('pridej-model.php');
    if (pridej_do_db2($newTask)) {
      require('pridano-view.html');
      exit;
    } else {
      $errorMessage = "Error úkol nebyl přidán";
    }
  } else {
    $errorMessage = "neplatné heslo";
  }
}

$predmety = [
  "ČJ",
  "MA",
  "AJ",
  "WA",
  "SV",
  "PG",
  "FY",
  "CT",
  "EN",
  "IN",
  "OS",
  "PX",
  "TV",
];

require('pridej-view.php');
?>
