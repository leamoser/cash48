<?php
//Initialisierung der Session und anderen Dateien
session_start();
require_once('system/config.php');
require_once('system/data.php');
require_once('system/sessionhandler.php');

$du = get_person_by_id($user_id);
$wg = get_wg_by_id($du['wg']);

?>


<?php include('template/head.php') ?>
<article class="intro">
    <h1><?php echo $du['name'] ?>'s Profil</h1>
</article>
<p><strong>din nutzername: </strong><?php echo $du['name'] ?></p>
<p><strong>din wg-name: </strong><?php echo $wg['name'] ?></p>
<p><strong>din kontostand: </strong><?php echo $du['value'] ?> CHF</p>
<?php include('template/foot.php') ?>