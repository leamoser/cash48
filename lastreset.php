<?php
//Initialisierung der Session und anderen Dateien
session_start();
require_once('system/config.php');
require_once('system/data.php');
require_once('system/sessionhandler.php');

$reset = get_latest_reset();
$datum = new DateTime($reset['date']);
if ($reset['person'] == 1) {
    $mensch = "Yann";
} elseif ($reset['person'] == 2) {
    $mensch = "Basil";
} elseif ($reset['person'] == 3) {
    $mensch = "Dominik";
} else {
    $mensch = "Lea";
};

?>


<?php include('template/head.php') ?>
<article class="intro">
    <h1>Alle Angaben zum letzten Kassensturz.</h1>
</article>

<h3>Durchgeführt am: <?php echo $datum->format('d. F Y') ?><br>Durchgeführt von: <?php echo $mensch ?></h3>
<p><strong>Yann: </strong> <?php echo $reset['value_yann'] ?></p>
<p><strong>Basil: </strong> <?php echo $reset['value_basil'] ?></p>
<p><strong>Dominik: </strong> <?php echo $reset['value_dominik'] ?></p>
<p><strong>Lea: </strong> <?php echo $reset['value_lea'] ?></p>
<?php include('template/foot.php') ?>