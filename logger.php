<?php 
$db = new SQLite3('data.sqlite');

function getClientIP() {
  if (!empty($_SERVER['HTTP_CLIENT_IP']))   
    {
      $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    }
  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
    {
      $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
  else
    {
      $ip_address = $_SERVER['REMOTE_ADDR'];
    }

  if ($ip_address == "::1" || $ip_address == "127.0.0.1") {
    return "localhost";
  } else {
    return $ip_address;    
  }
}

function logGet() {
  global $db;
  $stmt = $db->prepare('INSERT INTO getLogs(logMessage, ip) VALUES (?,?)');

  $now = new DateTime('now');
  $stmt->bindValue(1, "Get v " . $now->format("Y-m-d\ H:i:s.u"));
  $stmt->bindValue(2, getClientIP());
  
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}

function logAddition($newTask) {
  global $db;
  $res = $db->prepare('SELECT id, popis, predmet FROM ukoly WHERE popis = ? AND predmet = ? LIMIT 1');
  $res->bindValue(1, $newTask['popis']);
  $res->bindValue(2, $newTask['predmet']);  
  $x = $res->execute();
  $tm = $x->fetchArray();
  var_dump($tm);
  $id = $tm['id'];
  var_dump($id);

  $now = new DateTime('now');  
  $stmt = $db->prepare('INSERT INTO logs(logMessage, ip) VALUES (?,?)');
  $stmt->bindValue(1, "Pridan zaznam cislo " . $id . " v " . $now->format("Y-m-d\ H:i:s.u"));
  $stmt->bindValue(2, getClientIP());

  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}

?>