<?php
//Initialisierung der Session und anderen Dateien
session_start();
require_once('system/config.php');
require_once('system/data.php');
require_once('system/sessionhandler.php');

$wgmenschen = get_persons_by_wg($wg_id);

//Kassensturz machen
if (isset($_POST['make_reset'])) {
    //Reset eintragen und Reset-ID abrufen
    insert_reset($user_id, $wg_id);
    $lastreset = get_latest_reset_by_wg($wg_id);
    $reset_id = $lastreset['id'];
    //Array Mailadressen
    $mailadressen = array();

    //Einträge für die Einzelnen Personen
    foreach ($wgmenschen as $wgmensch) {
        //Details zum Mensch holen
        $wgmensch_id = $wgmensch['id'];
        $person = get_person_by_id($wgmensch_id);
        $wgmensch_value = $person['value'];
        $wgmensch_mail = $person['mail'];
        //Mailadressen in Array einfügen
        array_push($mailadressen, $wgmensch_mail);
        //In der Datenbank eintragen
        insert_details_reset($reset_id, $wgmensch_id, $wgmensch_value);
        //Beträge auf 0 zurücksetzen
        $resetwert = 0;
        values_updaten($resetwert, $wgmensch_id);
    };

    header('Location: /lastreset.php');
}
?>


<?php include('template/head.php') ?>
<article class="intro">
    <h1>wötsch alli iträg zruggsetze und en kassesturz mache?</h1>
</article>
<p>wenn du uf de chnopf klicksch, chasch die aktion nümme rückgängig mache. alli kontoständ werded uf 0 zruggsetzt. ufem scree nocher, und ide navigation unter letzte kassesturz gsehsch aber wer wem wieviel schuldet.</p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <button type="submit" name="make_reset" value="reset">jo, ich will</button>
</form>
<?php include('template/foot.php') ?>