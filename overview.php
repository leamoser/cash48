<?php
//Initialisierung der Session und anderen Dateien
session_start();
require_once('system/config.php');
require_once('system/data.php');
require_once('system/sessionhandler.php');

$menschen = get_all_persons();

?>


<?php include('template/head.php') ?>
<article class="intro">
    <h1>Aktuelle Übersicht</h1>
</article>
<?php foreach($menschen as $mensch){ ?>
<p><strong><?php echo $mensch['name'] ?>: </strong> <?php echo $mensch['value'] ?> CHF</p>
<?php } ?>
<a href="payment.php"><button>Neue Zahlung erfassen</button></a>
<?php include('template/foot.php') ?>