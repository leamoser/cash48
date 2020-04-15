<?php
//Initialisierung der Session und anderen Dateien
session_start();
require_once('system/config.php');
require_once('system/data.php');
require_once('system/sessionhandler.php');

$menschen = get_persons_by_wg($wg_id);
$eintragender = get_person_by_id($user_id);

if (isset($_POST['zahlung_eintragen'])) {
    $zahlung_eintragen = true;

    //Validierung Betrag
    if (!empty($_POST['betrag'])) {
        $zahlung_eintragen = true;
        $betrag = $_POST['betrag'];
    } else {
        $zahlung_eintragen = false;
    }

    //Validierung Beschreibung
    if (!empty($_POST['beschreibung'])) {
        $zahlung_eintragen = true;
        $beschreibung = $_POST['beschreibung'];
    } else {
        $zahlung_eintragen = false;
    }

    //Zuweisung
    if (!empty($_POST['zuweisung'])) {
        $zahlung_eintragen = true;
    } else {
        $zahlung_eintragen = false;
    }

    //Wenn alles OK dann Zeugs eintragen
    if ($zahlung_eintragen) {
        //Einträge bei Person die Zahlung einträgt
        insert_zahlung($user_id, $betrag, $beschreibung, $wg_id);
        $neuerwert_eintragender = $eintragender['value'] + $betrag;
        values_updaten($neuerwert_eintragender, $user_id);

        //Einträge in Splits für alle von Zahlung betroffenen Personen
        $idsbetroffene = $_POST['zuweisung'];
        $anzahlbetroffene = count($idsbetroffene);
        $aufgeteilterbetrag = $betrag / $anzahlbetroffene;
        $zahlung = get_latest_zahlung_by_wg($wg_id);
        $idzahlung = $zahlung['id'];
        foreach ($idsbetroffene as $idbetroffener) {
            $alleinfosbetroffener = get_person_by_id($idbetroffener);
            insert_split($idbetroffener, $aufgeteilterbetrag, $idzahlung);
            $neuerwert_split = $alleinfosbetroffener['value'] - $aufgeteilterbetrag;
            values_updaten($neuerwert_split, $idbetroffener);
        }
        header('Location: /overview.php');
    }
}

?>


<?php include('template/head.php') ?>
<article class="intro">
    <h1>salü <?php echo $user['name'] ?>.<br>do chasch du e neui zahlig erfasse</h1>
</article>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div>
        <label for="betrag">de betrag in CHF (nur ganzi zahle)</label><br>
        <input type="number" name="betrag" id="betrag">
    </div>
    <div>
        <label for="beschreibung">beschribig</label><br>
        <textarea name="beschreibung" id="beschreibung"></textarea>
    </div>
    <div>
        <p>persone, wo d'zahlig betrifft</p>
        <?php foreach ($menschen as $mensch) { ?>
            <div class="checks">
                <label class="container" for="<?php echo $mensch['name'] ?>">
                    <input id="<?php echo $mensch['name'] ?>" type="checkbox" name="zuweisung[]" value="<?php echo $mensch['id'] ?>">
                    <?php echo $mensch['name'] ?></label>
                <br>
            </div>
        <?php } ?>
    </div>
    <button type="submit" name="zahlung_eintragen" value="zahlung">zahlig iträge</button>
</form>
<?php include('template/foot.php') ?>