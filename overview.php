<?php
//Initialisierung der Session und anderen Dateien
session_start();
require_once('system/config.php');
require_once('system/data.php');
require_once('system/sessionhandler.php');

$menschen = get_persons_by_wg($wg_id);

?>


<?php include('template/head.php') ?>
<article class="intro">
    <h1>Eine<br>Übersicht 🏦</h1>
    <p>Hier siehst du, wie die Finanzen in deiner WG im Moment so aussehen.</p>
</article>
<?php foreach ($menschen as $mensch) { ?>
    <div value="<?php echo $mensch['value'] ?>" class="balken"></div>
    <p><strong><?php echo $mensch['name'] ?>: </strong> <?php echo round($mensch['value'], 1) ?> CHF</p>
<?php } ?>
<a href="payment.php"><button>Neue Zahlung erfassen</button></a>
<?php include('template/foot.php') ?>