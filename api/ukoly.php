<?php 
$db = new SQLite3('../data.sqlite');
header('Content-type: application/json');

function getUkoly() {
  global $db;
  $obj = [];
  $res = $db->query("SELECT id, predmet, popis, datum_zadani, datum_odevzdani, skupina, typ_zaznamu FROM ukoly WHERE datum_odevzdani >= date('now') ORDER BY datum_odevzdani ASC");
  
  while($row = $res->fetchArray(1)) {
    #var_dump($row);
    array_push($obj, $row);
  }

  echo(json_encode($obj));
};

function addUkol($json) {
  global $db;
  
  var_dump($json);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  getUkoly();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $json = json_decode(file_get_contents('php://input'));
  #var_dump($json);
  
  if (isset($json->action) && $json->action == "add") {
    addUkol();
  } else {
    echo "{\"status\" : \"error\"}";
  }
}
?>