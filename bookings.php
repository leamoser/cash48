<?php
//Initialisierung der Session und anderen Dateien
session_start();
require_once('system/config.php');
require_once('system/data.php');
require_once('system/sessionhandler.php');

//Datum letzter Reset holen
$lastreset = get_latest_reset_by_wg($wg_id);
$datumreset = $lastreset['date'];

//Zahlungen Anhand dieser Daten holen
$zahlungen = get_all_zahlungen_by_wg_and_date($wg_id, $datumreset);

?>


<?php include('template/head.php') ?>
<article class="intro">
    <h1>alli zahlige im aktuelle ziitruum (also nochem letzte kassesturz bis jetzt)</h1>
</article>
<div>
    <?php foreach ($zahlungen as $zahlung) {
        //Daten einzelhe Zahlungen holen
        $menschid = $zahlung['person'];
        $mensch = get_person_by_id($menschid);
        $datum = new DateTime($zahlung['date']);

        //Betroffene Menschen
        $zahlungsid = $zahlung['id'];
        $splits = get_splits_by_zahlung_id($zahlungsid);
        //Alle Namen der Betroffenen in einem neuen Array speichern
        $betroffene = array();
        foreach ($splits as $split) {
            $betroffener = get_person_by_id($split['person']);
            array_push($betroffene, $betroffener['name']);
        }

        ?>
        <div class="buchungen">
            <h3><?php echo $datum->format('d. F Y | G:i') ?></h3>
            <p><strong>was: </strong><?php echo $zahlung['description'] ?></p>
            <p><strong>vo wem erfasst: </strong><?php echo $mensch['name'] ?></p>
            <p><strong>betroffene: </strong><?php foreach ($betroffene as $person) {
                                                echo $person . ', ';
                                            } ?></p>
            <p><strong>betrag: </strong> <?php echo money_format('%.2n', $zahlung['value']) ?> CHF</p>
        </div>
    <?php } ?>
</div>
<?php include('template/foot.php') ?>