<?php
//Initialisierung der Session und anderen Dateien
session_start();
require_once('system/config.php');
require_once('system/data.php');
require_once('system/sessionhandler.php');

$wgmenschen = get_persons_by_wg($wg_id);

//Kassensturz machen
if (isset($_POST['make_reset'])) {
    //Reset eintragen und Reset-ID abrufen-------------------------
    insert_reset($user_id, $wg_id);
    $lastreset = get_latest_reset_by_wg($wg_id);
    $reset_id = $lastreset['id'];

    //Einträge für die Einzelnen Personen--------------------------
    foreach ($wgmenschen as $wgmensch) {
        //Details zum Mensch holen
        $wgmensch_id = $wgmensch['id'];
        $person = get_person_by_id($wgmensch_id);
        $wgmensch_value = $person['value'];
        //In der Datenbank eintragen
        insert_details_reset($reset_id, $wgmensch_id, $wgmensch_value);
        //Beträge auf 0 zurücksetzen
        $resetwert = 0;
        values_updaten($resetwert, $wgmensch_id);
    };

    //ANFANG: Einträge in Reset-Zahlungen machen----------------------
    $menschen = get_reset_details_by_id($reset_id);
    //Array mit Werten und Namen erstellen
    $werteundnamen = array();
    foreach ($menschen as $mensch) {
        $werteundnamen[$mensch['person']] = $mensch['value'];
    }
    //Endstand initialisieren
    $endstand;
    foreach ($werteundnamen as $wert) {
        $endstand += abs($wert);
    }
    //Einträge machen
    while ($endstand != 0) {
        $max = max($werteundnamen);
        $personmax = array_search($max, $werteundnamen);
        $min = min($werteundnamen);
        $personmin = array_search($min, $werteundnamen);

        if (abs($max) > abs($min)) {
            $werteundnamen[$personmax] -= abs($min);
            $werteundnamen[$personmin] = 0;
            $betrag = round(abs($min), 1);
            insert_zahlungen_reset($reset_id, $personmax, $personmin, $betrag);
            $text = $personmax . " bekommt von " . $personmin . " CHF " . round(abs($min), 1);
        } elseif (abs($max) < abs($min)) {
            $werteundnamen[$personmin] += abs($max);
            $werteundnamen[$personmax] = 0;
            $betrag = round($max, 1);
            insert_zahlungen_reset($reset_id, $personmax, $personmin, $betrag);
            $text = $personmax . " bekommt von " . $personmin . " CHF " . round($max, 1);
        } elseif (abs($max) == abs($min)) {
            $werteundnamen[$personmax] = 0;
            $werteundnamen[$personmin] = 0;
            $betrag = round($max, 1);
            insert_zahlungen_reset($reset_id, $personmax, $personmin, $betrag);
            $text = $personmax . " bekommt von " . $personmin . " CHF " . round($max, 1);
        } else {
            "Da ist aber ein Fehler passiert";
        }
        $endstand = 0;
        foreach ($werteundnamen as $wert) {
            $endstand += round(abs($wert));
        }
    }
    //ENDE: Einträge in Reset-Zahlungen machen----------------------------

    //Mail versenden------------------------------------------------------
    foreach ($wgmenschen as $wgmensch) {
        //Details zum Mensch holen
        $wgmensch_id = $wgmensch['id'];
        $person = get_person_by_id($wgmensch_id);
        $wgmensch_value = $person['value'];
        //Infos holen
        $datum_reset = new DateTime();
        $datum_final = $datum_reset->format('d. F Y');
        //finale Variabeln
        $header = array(
            'From' => 'cash48 <abrechnung@cash48.ch>',
            'Reply-To' => 'abrechnung@cash48.ch',
            'X-Mailer' => 'PHP/' . phpversion(),
            'Content-Type' => 'text/html; charset=utf-8'
        );
        $empfaenger = $person['mail'];
        $betreff = "Abrechnung Kassensturz: " . $datum_final . " .";
        $text = "Hallo " . $person['name'] . ",<br> Deine WG hat abgerechnet. Du musst <strong>Person X nn.nn CHF zahlen</strong>.<br> Liebe Gruess, dein cash-48 Team!";
        //Mail absenden
        mail($empfaenger, $betreff, $text, $header);
    }

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