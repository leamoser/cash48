<?php
//Initialisierung der Session und anderen Dateien
session_start();
require_once('system/config.php');
require_once('system/data.php');
require_once('system/sessionhandler.php');

//Datum des letzten Reset holen
$reset = get_latest_reset_by_wg($wg_id);
$datumreset = new DateTime($reset['date']);

//Ausführender Mensch eintragen
$menschid = $reset['person'];
$mensch = get_person_by_id($menschid);

//Alle Menschen der WG holen
$idreset = $reset['id'];
$details = get_reset_details_by_id($idreset);



?>


<?php include('template/head.php') ?>
<article class="intro">
    <h1>alli agobe zum de kassestürz vo dinere wg.</h1>
</article>

<h3>duregfüehrt am: <?php echo $datumreset->format('d. F Y | G:i') ?><br>duregfüehrt vo: <?php echo $mensch['name'] ?></h3>
<?php foreach ($details as $detail) {
    $personid = $detail['person'];
    $person = get_person_by_id($personid);
    ?>
    <p><strong><?php echo $person['name'] ?>: </strong> <?php echo $detail['value'] ?>CHF</p>
<?php } ?>
<?php include('template/foot.php') ?>