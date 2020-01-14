<?php 
$db = new SQLite3('data.sqlite');
$ted = new DateTime('now');  

class Logger {
  private function getClientIP() {
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
      return "localhost(" . $ip_address . ")" ;
    } else {
      return $ip_address;    
    }
  }
  
  public function getLog() {
    global $db;
    global $ted;
    
    $stmt = $db->prepare('INSERT INTO getLogs(ip, timeofaction) VALUES (?,?)');
    $stmt->bindValue(1, $this->getClientIP());
    $stmt->bindValue(2, $ted->format("Y-m-d\ H:i:s.u"));
      
    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }
  
  public function addLog($newTask) {
    global $db;
    global $ted;

    $res = ($db->query("SELECT id FROM ukoly ORDER BY id DESC LIMIT 1;"))->fetchArray();
    $id = $res['id'];

    $ipa = (string)$this->getClientIP();
    $stmt = $db->prepare('INSERT INTO logs (ip, timeofaction, act, msg) VALUES(?,?,?,?)');
    $stmt->bindValue(1, $ipa);
    $stmt->bindValue(2, $ted->format("Y-m-d\ H:i:s.u"));
    $stmt->bindValue(3, "add");
    $message = $id . ", " . $newTask['predmet'] . ", " . $newTask['popis'] . ", " . $newTask['datum_zadani'] . ", " . $newTask['datum_odevzdani'] . ", " . $newTask['typ_zaznamu'] . ', ' . $newTask['skupina'];
    $stmt->bindValue(4, $message);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  } 

  public function deleteLog() {
    #to be continued
  }

  #this is broken
  public function getGetLogs($limit) {
    global $db;
    $logs = [];
    $str = "";

    $res = $db->query('SELECT * FROM getLogs LIMIT ' . $limit);
    echo "<link href='style.css' rel='stylesheet'>";
    echo "<table>";
    #echo "<table><th>ID</th><th>IP</th><th>TimeDate</th>";
    while ($row = $res->fetchArray()) {
      #echo ("<tr><td>" .  $row['id'] . "</td><td>" . $row['ip'] . "</td><td>" . $row['timeofaction'] . "</td></tr>");
      require('./table.php');
      usleep(50);
    }
    #echo $str;  
    echo "</table>";
    
  }

}

if($_SERVER['REQUEST_METHOD'] == 'POST' && @$_SERVER['logs'] == 'yy') {
  if($_POST['passwd'] == "abcd") {
    $logger = new Logger;
    $logger->getGetLogs($_POST['limit']);
  } else {
    echo "incorrect password, reload";
  }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if(@$_GET['logs'] == "yes") {
    require('logger.html');
    exit();
  }
}
?>