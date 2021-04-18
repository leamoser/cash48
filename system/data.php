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
//Profilbild uploaden
function profilbild_updaten($dateiname, $id_person)
{
  $db = get_db_connection();
  $sql = "UPDATE menschen SET profilbild = ? WHERE menschen.id = ?";
  $stmt = $db->prepare($sql);
  $values = array($dateiname, $id_person);
  return $stmt->execute($values);
}
//Neuster Nutzer holen
function get_latest_mensch()
{
  $db = get_db_connection();
  $sql = "SELECT * FROM menschen ORDER BY id DESC LIMIT 1";
  $result = $db->query($sql);
  return $result->fetch();
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
function get_latest_wg()
{
  $db = get_db_connection();
  $sql = "SELECT * FROM wg ORDER BY id DESC LIMIT 1";
  $result = $db->query($sql);
  return $result->fetch();
}
function count_wgs()
{
  $db = get_db_connection();
  $sql = "SELECT COUNT(name) FROM wg";
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
function get_reset_zahlungen_by_reset_id($idreset)
{
  $db = get_db_connection();
  $sql = "SELECT * FROM reset_zahlungen WHERE reset='$idreset'";
  $result = $db->query($sql);
  return $result->fetchAll();
}

//BEZAHLEN UND EMPFANGEN-------------------------------
//Ich muss bezahlen
function get_offene_zahlungen_by_user_id($user_id)
{
  $db = get_db_connection();
  $sql = "SELECT * FROM reset_zahlungen WHERE zahler='$user_id' AND bezahlt=0";
  $result = $db->query($sql);
  return $result->fetchAll();
}
//Status bezahlt updaten
function update_status_bezahlt_by_reset_zahlung_id($reset_zahlung_id)
{
  $db = get_db_connection();
  $sql = "UPDATE reset_zahlungen SET bezahlt = 1 WHERE id = ?";
  $stmt = $db->prepare($sql);
  $values = array($reset_zahlung_id);
  return $stmt->execute($values);
}
//Ich bekomme 
function get_offene_empfaenge_by_user_id($user_id)
{
  $db = get_db_connection();
  $sql = "SELECT * FROM reset_zahlungen WHERE empfaenger='$user_id' AND empfangen=0";
  $result = $db->query($sql);
  return $result->fetchAll();
}
//Status empfangen updaten
function update_status_empfangen_by_reset_zahlung_id($reset_zahlung_id)
{
  $db = get_db_connection();
  $sql = "UPDATE reset_zahlungen SET empfangen = 1, bezahlt = 1 WHERE id = ?";
  $stmt = $db->prepare($sql);
  $values = array($reset_zahlung_id);
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
//WG REGISTRIEREN---------------------------------------
function insert_wg($wgname)
{
  $db = get_db_connection();
  $sql = "INSERT INTO wg (name) VALUES (?)";
  $stmt = $db->prepare($sql);
  $values = array($wgname);
  return $stmt->execute($values);
}
function insert_mensch($nn, $pw, $mail, $wgid)
{
  $db = get_db_connection();
  $sql = "INSERT INTO menschen (name, pass, mail, wg) VALUES (?, ?, ?, ?)";
  $stmt = $db->prepare($sql);
  $values = array($nn, $pw, $mail, $wgid);
  return $stmt->execute($values);
}

//EINKAUFSLISTE--------------------------------------------
function insert_product($produkt_name, $wg_id, $status_prod, $user_id)
{
  $db = get_db_connection();
  $sql = "INSERT INTO einkaufsliste (produkt, wg, status, user) VALUES (?, ?, ?, ?)";
  $stmt = $db->prepare($sql);
  $values = array($produkt_name, $wg_id, $status_prod, $user_id);
  return $stmt->execute($values);
}
function get_products_by_wg($wg_id)
{
  $db = get_db_connection();
  $sql = "SELECT * FROM einkaufsliste WHERE wg='$wg_id' AND status=1 ORDER BY id DESC";
  $result = $db->query($sql);
  return $result->fetchAll();
}
function update_product_status($id_product)
{
  $db = get_db_connection();
  $sql = "UPDATE einkaufsliste SET status = 0 WHERE id = ?";
  $stmt = $db->prepare($sql);
  $values = array($id_product);
  return $stmt->execute($values);
}

//TRÄUME--------------------------------------------
function insert_traum($traum_desc, $user_id, $wg_id)
{
    $db = get_db_connection();
    $sql = "INSERT INTO traume (description, user, wg) VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql);
    $values = array($traum_desc, $user_id, $wg_id);
    return $stmt->execute($values);
}
function get_dreams_by_wg($wg_id)
{
    $db = get_db_connection();
    $sql = "SELECT * FROM traume WHERE wg='$wg_id' ORDER BY id DESC";
    $result = $db->query($sql);
    return $result->fetchAll();
}
function count_dreams_by_person($user_id)
{
    $db = get_db_connection();
    $sql = "SELECT COUNT(description) FROM traume WHERE user = '$user_id'";
    $result = $db->query($sql);
    return $result->fetch();
}