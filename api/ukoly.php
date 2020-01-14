<?php
$db = new SQLite3('../data.sqlite');
$now = new DateTime('now'); 
header('Content-type: application/json');

function getTasks() {
  global $db;
  global $now;
  $resp = (object)[];
  $obj = [];
  $res = @$db->query("SELECT id, predmet, popis, datum_zadani, datum_odevzdani, skupina, typ_zaznamu FROM ukoly WHERE datum_odevzdani >= date('now') ORDER BY datum_odevzdani ASC");
  
  if ($res != false) {
    while($row = $res->fetchArray(1)) {
      array_push($obj, $row);
    }
    $resp->status = "success";
    $resp->timeDate = $now->format("Y-m-d\ H:i:s.u");
    $resp->tasks = $obj;
  } else {
    $resp->status = "error";
    $resp->timeDate = $now->format("Y-m-d\ H:i:s.u");
  }

  
  echo(json_encode($resp));
};

function addTask($json) {
  global $db; 
  global $now;
  $resp = (object)[];
  $predmety = ["ČJ","MA","AJ","WA","SV","PG","FY","CT","OZ","EN","IN","OS","PX","TV"];
  if (!in_array($json->predmet, $predmety)) {
    sendErrorMessage("predmet is wrong");
    return;
  }

  $stmt = $db->prepare('INSERT INTO ukoly(predmet, popis, datum_zadani, datum_odevzdani, typ_zaznamu, skupina) VALUES (?,?,?,?,?,?)');
  $stmt->bindValue(1, $json->predmet);
  $stmt->bindValue(2, $json->popis);
  $stmt->bindValue(3, $json->datum_zadani);
  $stmt->bindValue(4, $json->datum_odevzdani);
  $stmt->bindValue(5, $json->skupina);
  $stmt->bindValue(6, $json->typ_zaznamu);

  if (@$stmt->execute()) {
    $resp->status = "success";
    $resp->timeDate = $now->format("Y-m-d\ H:i:s.u");
    $resp->newTask = $json;

    echo(json_encode($resp));
  } else {
    sendErrorMessage(""); 
  }  
}

function editTask($json) {
  global $db;
  global $now;
  $resp = (object)[];

  if (!isset($json->id)) {
    sendErrorMessage("id not specified");
    return;
  }

  $q = $db->prepare('SELECT EXISTS(SELECT 1 FROM ukoly WHERE id=:id)');
  $q->bindValue(':id', $json->id);
  $temp = $q->execute();
  if ($temp->fetchArray()['0'] == 0) {
    sendErrorMessage("id does not exist");
    return;
  }
  
  $stmt = $db->prepare('UPDATE ukoly SET (predmet,popis,datum_odevzdani,datum_zadani,typ_zaznamu,skupina)=(:predmet,:popis,:datum_odevzdani,:datum_zadani,:typ_zaznamu,:skupina) WHERE id=:id');
  $stmt->bindValue(':id', $json->id);
  $stmt->bindValue(':predmet', $json->predmet);
  $stmt->bindValue(':popis', $json->popis);
  $stmt->bindValue(':datum_odevzdani', $json->datum_odevzdani);
  $stmt->bindValue(':datum_zadani', $json->datum_zadani);
  $stmt->bindValue(':typ_zaznamu', $json->typ_zaznamu);
  $stmt->bindValue(':skupina', $json->skupina);
  
  if (@$stmt->execute()) {
    $resp->status = "success";
    $resp->timeDate = $now->format("Y-m-d\ H:i:s.u");
    $resp->updated = $json;

    echo(json_encode($resp));
  } else {
    sendErrorMessage("");  
  }
  
}

function postHandler($postJson) {
  global $now;
  $resp = (object)[];
  $json = json_decode($postJson);
  
  if (isset($json->action)) {
    if ($json->action == "add") {
      addTask($json);
    } elseif ($json->action == "edit") {
      editTask($json);  
    } else {
      sendErrorMessage("unknown action");
    }
  } else {
    sendErrorMessage("action not specified");
  }
  
}

function sendErrorMessage($errMsg) {
  global $now;
  $resp = (object)[];
  $resp->status = "error";
  if ($errMsg != "") {
    $resp->errorMessage = $errMsg;
  }
  $resp->timeDate = $now->format("Y-m-d\ H:i:s.u"); 
  
  echo(json_encode($resp));
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  getTasks();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
  postHandler(file_get_contents('php://input'));
}
?>
<!-- ADD SECURITTY -->