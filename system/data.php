<?php
//Datenbankverbindung
function get_db_connection()
{

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
//Abfragen--------------------------------------------
//Abfragen--------------------------------------------

//MENSCHEN--------------------------------------------
//Alle Menschen einer WG holen
function get_persons_by_wg($wg)
{
  $db = get_db_connection();
  $sql = "SELECT * FROM menschen WHERE wg='$wg'";
  $result = $db->query($sql);
  return $result->fetchAll();
}
//Mensch anhand ID holen
function get_person_by_id($id)
{
  $db = get_db_connection();
  $sql = "SELECT * FROM menschen WHERE id='$id'";
  $result = $db->query($sql);
  return $result->fetch();
}
//Werte updaten in Tabelle Menschen
function values_updaten($neuerwert, $id_person)
{
  $db = get_db_connection();
  $sql = "UPDATE menschen SET value = ? WHERE menschen.id = ?";
  $stmt = $db->prepare($sql);
  $values = array($neuerwert, $id_person);
  return $stmt->execute($values);
}

//ZAHLUNGEN--------------------------------------------
//Alle Zahlungen holen einer WG holen
function get_all_zahlungen_by_wg($wg)
{
  $db = get_db_connection();
  $sql = "SELECT * FROM zahlungen WHERE wg='$wg' ORDER BY zahlungen.id DESC";
  $result = $db->query($sql);
  return $result->fetchAll();
}
//Ale Zahlungen einer WG nach dem letzten Kassensturz
function get_all_zahlungen_by_wg_and_date($wg, $datum)
{
  $db = get_db_connection();
  $sql = "SELECT * FROM zahlungen WHERE wg='$wg' AND date>'$datum' ORDER BY zahlungen.id DESC";
  $result = $db->query($sql);
  return $result->fetchAll();
}
//Zahlung eintragen
function insert_zahlung($user_id, $betrag, $beschreibung, $wg_id)
{
  $db = get_db_connection();
  $sql = "INSERT INTO zahlungen (person, value, description, wg) VALUES (?, ?, ?, ?)";
  $stmt = $db->prepare($sql);
  $values = array($user_id, $betrag, $beschreibung, $wg_id);
  return $stmt->execute($values);
}
//ID von der letzten Zahlung rausfinden
function get_latest_zahlung_by_wg($wg_id)
{
  $db = get_db_connection();
  $sql = "SELECT * FROM zahlungen WHERE wg='$wg_id' ORDER BY id DESC LIMIT 1";
  $result = $db->query($sql);
  return $result->fetch();
}

//WG'S-------------------------------------------------
//WG anhand ID holen
function get_wg_by_id($id)
{
  $db = get_db_connection();
  $sql = "SELECT * FROM wg WHERE id='$id'";
  $result = $db->query($sql);
  return $result->fetch();
}

//RESETS-----------------------------------------------
//Reset eintragen
function insert_reset($user_id, $wg_id)
{
  $db = get_db_connection();
  $sql = "INSERT INTO resets (person, wg) VALUES (?, ?)";
  $stmt = $db->prepare($sql);
  $values = array($user_id, $wg_id);
  return $stmt->execute($values);
}
//Letzer Reset holen
function get_latest_reset_by_wg($wg_id)
{
  $db = get_db_connection();
  $sql = "SELECT * FROM resets WHERE wg='$wg_id' ORDER BY id DESC LIMIT 1";
  $result = $db->query($sql);
  return $result->fetch();
}
//Alle Resets einer WG holen
function get_resets_by_wg($wg_id)
{
  $db = get_db_connection();
  $sql = "SELECT * FROM resets WHERE wg='$wg_id' ORDER BY id DESC";
  $result = $db->query($sql);
  return $result->fetchAll();
}

//RESETS-DETAILS--------------------------------------
function get_reset_details_by_id($idreset)
{
  $db = get_db_connection();
  $sql = "SELECT * FROM reset_details WHERE reset='$idreset'";
  $result = $db->query($sql);
  return $result->fetchAll();
}

//SPLITS----------------------------------------------
//Splits eintragen
function insert_split($user_id, $aufgeteilterbetrag, $idzahlung)
{
  $db = get_db_connection();
  $sql = "INSERT INTO splits (person, value, zahlung) VALUES (?, ?, ?)";
  $stmt = $db->prepare($sql);
  $values = array($user_id, $aufgeteilterbetrag, $idzahlung);
  return $stmt->execute($values);
}
//Alle Splits einer Zahlungs-ID holen
function get_splits_by_zahlung_id($zahlungsid)
{
  $db = get_db_connection();
  $sql = "SELECT * FROM splits WHERE zahlung='$zahlungsid'";
  $result = $db->query($sql);
  return $result->fetchAll();
}

//KASSENSTURZ--------------------------------------------
function insert_details_reset($reset_id, $wgmensch_id, $wgmensch_value)
{
  $db = get_db_connection();
  $sql = "INSERT INTO reset_details (reset, person, value) VALUES (?, ?, ?)";
  $stmt = $db->prepare($sql);
  $values = array($reset_id, $wgmensch_id, $wgmensch_value);
  return $stmt->execute($values);
}
function insert_zahlungen_reset($reset_id, $empfaenger, $zahler, $betrag)
{
  $db = get_db_connection();
  $sql = "INSERT INTO reset_zahlungen (reset, empfaenger, zahler, betrag) VALUES (?, ?, ?, ?)";
  $stmt = $db->prepare($sql);
  $values = array($reset_id, $empfaenger, $zahler, $betrag);
  return $stmt->execute($values);
}

//LOGIN------------------------------------------------
//Login
function login($login_nn, $login_pw)
{
  $db = get_db_connection();
  $sql = "SELECT * FROM menschen WHERE name='$login_nn' AND pass='$login_pw'";
  $result = $db->query($sql);
  if ($result->rowCount() == 1) {
    return $result->fetch();
  } else {
    return false;
  }
}
