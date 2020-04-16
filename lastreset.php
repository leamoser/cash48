<?php
//Initialisierung der Session und anderen Dateien
session_start();
require_once('system/config.php');
require_once('system/data.php');
require_once('system/sessionhandler.php');

//Alle Resets holen
$resets = get_resets_by_wg($wg_id);

?>


<?php include('template/head.php') ?>
<article class="intro">
    <h1>Alle<br>Kassenstürze.</h1>
    <p>Hier siehst du alle Angaben zu den Kassenstürzen in deiner WG.</p>
</article>

<?php foreach ($resets as $reset) {
    $datumreset = new DateTime($reset['date']);
    //Ausführender Mensch eintragen
    $menschid = $reset['person'];
    $mensch = get_person_by_id($menschid);
    //Alle Menschen der WG holen
    $idreset = $reset['id'];
    $details = get_reset_details_by_id($idreset);
    $zahlungen = get_reset_zahlungen_by_reset_id($idreset);
    ?>
    <div class="reset">
        <p><strong>Datum: </strong><?php echo $datumreset->format('d. F Y | G:i') ?><br><strong>Von wem gemacht:</strong> <?php echo $mensch['name'] ?></p>
        <?php foreach ($zahlungen as $zahlung) {
            $empfaenger = get_person_by_id($zahlung['empfaenger']);
            $zahler = get_person_by_id($zahlung['zahler']);
            if ($zahlung['bezahlt']) {
                $bezahlt = "Ja";
            } else {
                $bezahlt = "Nein";
            }
            if ($zahlung['empfangen']) {
                $empfangen = "Ja";
            } else {
                $empfangen = "Nein";
            }
            ?>
            <article class="resetbox">
                <p class="satz"><strong><?php echo $zahler['name'] . " muss " . $empfaenger['name'] . " " . $zahlung['betrag'] . " CHF bezahlen."  ?></strong></p>
                <div class="stati">
                    <p>Bezahlt: <?php echo $bezahlt ?></p>
                    <div value="<?php echo $zahlung['bezahlt'] ?>" class="status"></div>
                    <p>Angekommen: <?php echo $empfangen ?></p>
                    <div value="<?php echo $zahlung['empfangen'] ?>" class="status"></div>
                </div>
            </article>
        <?php } ?>
    </div>
<?php } ?>
<?php include('template/foot.php') ?>