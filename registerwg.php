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
if (isset($_POST['register_wg_submit'])) {
    $register_valid = true;
    //Validierung WG-Name
    if (!empty($_POST['wgname'])) {
        $wgname = $_POST['wgname'];
    } else {
        $msg .= "Du hast keinen WG-Namen eingegeben<br>";
        $register_valid = false;
    }
    //Validierung Mailadresse
    if (!empty($_POST['mailadresse'])) {
        $mail = $_POST['mailadresse'];
    } else {
        $msg .= "Du hast keine Mailadresse eingegeben<br>";
        $register_valid = false;
    }
    //Validierung Nutzername
    if (!empty($_POST['nn'])) {
        $nn = $_POST['nn'];
    } else {
        $msg .= "Du hast keinen Nutzernamen eingegeben<br>";
        $register_valid = false;
    }
    //Validierung Passwort
    if (!empty($_POST['pw'])) {
        $pw = $_POST['pw'];
    } else {
        $msg .= "Du hast kein Passwort eingegeben<br>";
        $register_valid = false;
    }
    //WENN VALIDIERUNG OK
    if ($register_valid) {
        insert_wg($wgname);
        $new_wg = get_latest_wg();
        $new_wg_id = $new_wg['id'];
        $secretkey = $new_wg_id * 327 + 182;
        insert_mensch($nn, $pw, $mail, $new_wg_id);
        $user = get_latest_mensch();
        //Mail versenden----------------
        $header = array(
            'From' => 'cash48 <info@cash48.ch>',
            'Reply-To' => 'info@cash48.ch',
            'X-Mailer' => 'PHP/' . phpversion(),
            'Content-Type' => 'text/html; charset=utf-8'
        );
        $empfaenger = $_POST['mailadresse'];
        $betreff = "Deine Neue WG.";
        $text = "Hallo " . $nn . ",<br> Du hast einen neue WG erstellt. Sie heisst <strong>" . $wgname .  "</strong>. <br> Teile deinen Mitbewohner*innen folgenden Key mit. Mit dem können Sie sich in die WG einloggen.<br><br><strong>Key: " . $secretkey . "</strong><br><strong>Link: <a href'https://www.cash48.ch/register.php'>https://www.cash48.ch/register.php</a></strong><br><br>Beste Grüsse, dein cash48-Team.";
        mail($empfaenger, $betreff, $text, $header);
        //Mail versenden----------------
        header('Location: /profile.php');
        session_start();
        $_SESSION['userid'] = $user['id'];
    } else {
        $msg .= "Das hat leider nicht geklappt. Upsi lol.";
    }
}
?>

<?php include('template/head.php') ?>
<article class="intro">
    <h1>Neue WG<br>registrieren</h1>
    <p>Hier kannst du eine neue WG eröffnen. Deine WG-Mitbewohner*innen kannst du in einem späteren Schritt einladen. </p>
</article>
<?php if (strlen($msg) != 0) { ?>
    <div class="error">
        <?php echo $msg; ?>
    </div>
<?php } ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <p>WG-Name</p>
    <input type="text" name="wgname" id="id_wgname">
    <p>Deine Mailadresse</p>
    <input type="text" name="mailadresse" id="id_mailadresse">
    <p>Dein Nutzername</p>
    <input type="text" name="nn" id="id_nn">
    <p>Dein Passwort</p>
    <input type="password" name="pw" id="id_pw">
    <p>Wenn du deine WG registriert hast, kannst du dich mit diesem Login einloggen. Per Mail bekommst du einen WG-Key. Leite diesen deinen WG-Mitbewohner*innen weiter. Mit diesem Key können sie sich dann für die WG registrieren.</p>
    <button type="submit" name="register_wg_submit" value="register">WG registrieren und einloggen</button>
</form>
<a href="/index.php">
    <p>-> Einloggen</p>
</a>
<a href="/register.php">
    <p>-> für bestehende WG registrieren</p>
</a>

<?php include('template/foot.php') ?>