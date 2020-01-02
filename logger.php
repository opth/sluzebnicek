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
      return "localhost";
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
    $ipa = (string)$this->getClientIP();
    var_dump($this->getClientIP());
    var_dump($ipa);
    $stmt = $db->prepare('INSERT INTO logs (ip, timeofaction, act, msg) VALUES(?,?,?,?)');
    $stmt->bindValue(1, $ipa);
    $stmt->bindValue(2, $ted->format("Y-m-d\ H:i:s.u"));
    $stmt->bindValue(3, "add");
    $message = $newTask['predmet'] . ", " . $newTask['datum_zadani'] . ", " . $newTask['datum_odevzdani'] . ", " . $newTask['typ_zaznamu'] . ', ' . $newTask['skupina'];
    $stmt->bindValue(4, $message);

    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  } 

}
?>