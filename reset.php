<?php
//Initialisierung der Session und anderen Dateien
session_start();
require_once('system/config.php');
require_once('system/data.php');
require_once('system/sessionhandler.php');
$menschen = get_all_persons();


if (isset($_POST['make_reset'])) {
    $yann = get_person_by_id(1);
    $basil = get_person_by_id(2);
    $dominik = get_person_by_id(3);
    $lea = get_person_by_id(4);
    $v_yann = $yann['value'];
    $v_basil = $basil['value'];
    $v_dominik = $dominik['value'];
    $v_lea = $lea['value'];
    $neuerwert = 0;
    insert_reset($user_id, $v_yann, $v_basil, $v_dominik, $v_lea);
    foreach ($menschen as $mensch) {
        $person_id = $mensch['id'];
        values_updaten($neuerwert, $person_id);
    }
    header('Location: /lastreset.php');
}
?>


<?php include('template/head.php') ?>
<article class="intro">
    <h1>Willst du die aktuellen Einträge zurücksetzen und einen Kassensturz machen?</h1>
</article>
<p>Diese Aktion ist final. Der aktuelle Stand wird bei allen auf 0 zurückgesetzt. Auf dem Nachfolgenden Screen siehts du, wer wem was schuldet.</p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <button type="submit" name="make_reset" value="reset">Ja, ich will.</button>
</form>
<?php include('template/foot.php') ?>