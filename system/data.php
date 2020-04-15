<?php
//Datenbankverbindung
function get_db_connection(){
  
    global $db_host, $db_name, $db_user, $db_pass, $db_charset;
  
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=$db_charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false
    ];
  
    try {
         $db = new PDO($dsn, $db_user, $db_pass, $options);
    } catch (\PDOException $e) {
         throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
  
    return $db;
}

//Abfragen--------------------------------------------
//Alle Menschen-Angaben holen
function get_all_persons(){
    $db = get_db_connection();
    $sql = "SELECT * FROM menschen";
    $result = $db->query($sql);
    return $result->fetchAll();
}
//Alle zahlungen holen
function get_all_zahlungen(){
  $db = get_db_connection();
  $sql = "SELECT * FROM zahlungen ORDER BY zahlungen.id DESC";
  $result = $db->query($sql);
  return $result->fetchAll();
}
//Mensch anhand ID holen
function get_person_by_id($id){
    $db = get_db_connection();
    $sql = "SELECT * FROM menschen WHERE id='$id'";
    $result = $db->query($sql);
    return $result->fetch();
}
//Zahlung eintragen
function insert_zahlung($user_id, $betrag, $beschreibung){
  $db = get_db_connection();
  $sql = "INSERT INTO zahlungen (person, value, description) VALUES (?, ?, ?)";
  $stmt = $db->prepare($sql);
  $values = array($user_id, $betrag, $beschreibung);
  return $stmt->execute($values);
}
//Reset eintragen
function insert_reset($user_id, $v_yann, $v_basil, $v_dominik, $v_lea){
  $db = get_db_connection();
  $sql = "INSERT INTO resets (person, value_yann, value_basil, value_dominik, value_lea) VALUES (?, ?, ?, ?, ?)";
  $stmt = $db->prepare($sql);
  $values = array($user_id, $v_yann, $v_basil, $v_dominik, $v_lea);
  return $stmt->execute($values);
}
//Werte updaten in Tabelle Menschen
function values_updaten($neuerwert ,$id_person){
  $db = get_db_connection();
  $sql = "UPDATE menschen SET value = ? WHERE menschen.id = ?";
  $stmt = $db->prepare($sql);
  $values = array($neuerwert ,$id_person);
  return $stmt->execute($values);
}
//ID von der Bezahlung rausfinden
function get_latest_zahlung(){
  $db = get_db_connection();
  $sql = "SELECT * FROM zahlungen ORDER BY id DESC LIMIT 1";
  $result = $db->query($sql);
  return $result->fetch();
}
//Letzer Reset holen
function get_latest_reset(){
  $db = get_db_connection();
  $sql = "SELECT * FROM resets ORDER BY id DESC LIMIT 1";
  $result = $db->query($sql);
  return $result->fetch();
}
//Splits eintragen
function insert_split($user_id, $aufgeteilterbetrag, $idzahlung){
  $db = get_db_connection();
  $sql = "INSERT INTO splits (person, value, zahlung) VALUES (?, ?, ?)";
  $stmt = $db->prepare($sql);
  $values = array($user_id, $aufgeteilterbetrag, $idzahlung);
  return $stmt->execute($values);
}
//Login
function login($login_nn, $login_pw){
    $db = get_db_connection();
    $sql = "SELECT * FROM menschen WHERE name='$login_nn' AND pass='$login_pw'";
    $result = $db->query($sql);
    if($result->rowCount() == 1) {
      return $result->fetch();
    } else {
      return false;
    }
  }

?>