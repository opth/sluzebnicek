<?php 
$db = new SQLite3('../data.sqlite');
header('Content-type: application/json');
$now = new DateTime('now'); 

function verze_sqlite() {
  global $db;
  @$version = $db->querySingle('SELECT SQLITE_VERSION()');
  return $version;
}

$obj = (object)[];
$obj->ok = true;
if (verze_sqlite() != false) {
  $obj->database = true;
  $obj->sqliteVersion = verze_sqlite();
} else {
  $obj->database = false;
}
$obj->timeDate =  $now->format("Y-m-d\ H:i:s.u");
$obj->signature = "89.248.243.38";

$json = json_encode($obj);
echo $json;
?>