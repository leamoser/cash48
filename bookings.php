<?php
//Initialisierung der Session und anderen Dateien
session_start();
require_once('system/config.php');
require_once('system/data.php');
require_once('system/sessionhandler.php');

$menschen = get_all_persons();
$zahlungen = get_all_zahlungen();

?>


<?php include('template/head.php') ?>
<article class="intro">
    <h1>Alle Buchungen</h1>
</article>
<div>
    <?php foreach($zahlungen as $zahlung){ 
        if($zahlung['person'] == 1){
            $mensch = "Yann";
        }elseif($zahlung['person'] == 2){
            $mensch = "Basil";
        }elseif($zahlung['person'] == 3){
            $mensch = "Dominik";
        }else{
            $mensch = "Lea";
        };
        $datum = new DateTime($zahlung['date']);
        ?>
        <div class="buchungen">
            <h3><?php echo $datum->format('d. F Y') ?></h3>
            <p><strong>Was: </strong><?php echo $zahlung['description'] ?></p>
            <p><strong>Von wem: </strong><?php echo $mensch ?></p>
            <p><strong>Betrag: </strong> <?php echo $zahlung['value'] ?></p>
        </div>
    <?php } ?>
</div>
<?php include('template/foot.php') ?>