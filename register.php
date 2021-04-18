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
//Registrierung
$msg = "";
if (isset($_POST['register_for_wg_submit'])) {
    $register_wg_valid = true;
    $key_valid = true;
    //Validierung Secret Key
    if (!empty($_POST['secretkey'])) {
        $secretkey = intval($_POST['secretkey']);
        $wg_id = ($secretkey - 182) / 327;
        if (is_int($wg_id)) {
            $msg .= "Dein Key ist valide<br>";
        } else {
            $msg .= "Dein Key ist falsch<br>";
            $key_valid = false;
        }
    } else {
        $msg .= "Du hast keinen Secretkey eingegeben<br>";
        $register_wg_valid = false;
    }
    //Validierung Mailadresse
    if (!empty($_POST['mailadresse'])) {
        $mail = $_POST['mailadresse'];
    } else {
        $msg .= "Du hast keine Mailadresse eingegeben<br>";
        $register_wg_valid = false;
    }
    //Validierung Nutzername
    if (!empty($_POST['nn'])) {
        $nn = $_POST['nn'];
    } else {
        $msg .= "Du hast keinen Nutzernamen eingegeben<br>";
        $register_wg_valid = false;
    }
    //Validierung Passwort
    if (!empty($_POST['pw'])) {
        $pw = $_POST['pw'];
    } else {
        $msg .= "Du hast kein Passwort eingegeben<br>";
        $register_wg_valid = false;
    }
    if ($register_wg_valid && $key_valid) {
        insert_mensch($nn, $pw, $mail, $wg_id);
        $user = get_latest_mensch();
        header('Location: /profile.php');
        session_start();
        $_SESSION['userid'] = $user['id'];
    } else { }
}
?>

<?php include('template/head.php') ?>
<article class="intro">
    <h1>Für eine WG<br>registrieren 🧬</h1>
    <p>Hier kannst du dich für eine schon bestehende WG registrieren. Den Secretkey solltest du von der Person, die die WG erstellt hat, bekommen haben.</p>
</article>
<?php if (strlen($msg) != 0) { ?>
    <div class="error">
        <?php echo $msg; ?>
    </div>
<?php } ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <p>Secret Key</p>
    <input type="number" name="secretkey" id="id_secretkey">
    <p>Deine Mailadresse</p>
    <input type="email" name="mailadresse" id="id_mailadresse">
    <p>Dein Nutzername</p>
    <input type="text" name="nn" id="id_nn" minlength="2" maxlength="50">
    <p>Dein Passwort (mind. 4 Zeichen)</p>
    <input type="password" name="pw" id="id_pw" minlength="4" maxlength="50">
    <button type="submit" name="register_for_wg_submit" value="register_for_wg">Für WG registrieren</button>
</form>
<a href="/index.php">
    <p>-> Einloggen</p>
</a>
<a href="/registerwg.php">
    <p>-> Neue WG registrieren</p>
</a>

<?php include('template/foot.php') ?>