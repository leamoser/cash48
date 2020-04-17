<?php
//Initialisierung der Session und anderen Dateien
session_start();
require_once('system/config.php');
require_once('system/data.php');
//Wenn eine Session vorhanden, dann ausloggen (abmelden)
if (isset($_SESSION['userid'])) {
  unset($_SESSION['userid']);
  session_destroy();
}
//Benachrichtigung bzgl. Loginfehler
$msg = "";
//Loginprozess und Validierung
if (isset($_POST['login_submit'])) {
  $login_valid = true;

  //Validierung Email
  if (!empty($_POST['nn'])) {
    $login_nn = $_POST['nn'];
  } else {
    $msg .= "Kein Nutzername eingegeben<br>";
    $login_valid = false;
  }

  //Validierung Passwort
  if (!empty($_POST['pw'])) {
    $login_pw = $_POST['pw'];
  } else {
    $msg .= "Kein Passwort eingegeben.<br>";
    $login_valid = false;
  }

  //Wenn Validierungen bestanden abgleichen
  if ($login_valid) {
    $result = login($login_nn, $login_pw);
    if ($result) {
      $user = $result;
      header('Location: /payment.php');
      //Session starten
      session_start();
      //Abspeichern der User-ID
      $_SESSION['userid'] = $user['id'];
    } else {
      $msg .= "Login hat nicht geklappt, Passwort und/oder Nutzername ist falsch!";
    }
  } else { };
}
//WG's zählen
$anzahl = count_wgs();

?>

<?php include('template/head.php') ?>
<article class="intro">
  <h1>cash48</h1>
  <p>Deine WG-App des Vertrauen.</p>
  <p>Bereits <span id="mark"><?php echo $anzahl['COUNT(name)']; ?></span> glückliche WG's.</p>
</article>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <p>Nutzername</p>
  <input type="text" name="nn" id="id_nn">
  <p>Passwort</p>
  <input type="password" name="pw" id="id_pw">
  <?php if (strlen($msg) != 0) { ?>
    <div class="error">
      <?php echo $msg; ?>
    </div>
  <?php } ?>
  <button type="submit" name="login_submit" value="einloggen">Anmelden</button>
</form>
<a href="/registerwg.php">
  <p>-> neue WG registrieren</p>
</a>
<a href="/register.php">
  <p>-> für bestehende WG registrieren.</p>
</a>

<?php include('template/foot.php') ?>