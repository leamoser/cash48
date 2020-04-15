<?php
//Initialisierung der Session und anderen Dateien
session_start();
require_once('system/config.php');
require_once('system/data.php');
require_once('system/sessionhandler.php');

$du = get_person_by_id($user_id);

?>


<?php include('template/head.php') ?>
<article class="intro">
    <h1><?php echo $du['name'] ?>'s Profil</h1>
</article>
<p><strong>Nutzername: </strong><?php echo $du['name'] ?></p>
<p><strong>Kontostand: </strong><?php echo $du['value'] ?></p>
<?php include('template/foot.php') ?>