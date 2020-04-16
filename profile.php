<?php
//Initialisierung der Session und anderen Dateien
session_start();
require_once('system/config.php');
require_once('system/data.php');
require_once('system/sessionhandler.php');

$du = get_person_by_id($user_id);
$wg = get_wg_by_id($du['wg']);
$wgmitbewohner = get_persons_by_wg($wg_id);

?>


<?php include('template/head.php') ?>
<article class="intro">
    <h1><?php echo $du['name'] ?>'s<br> Profil.</h1>
    <p>Hier siehst du alle Angaben zu dir, deiner WG und den Zahlungen die du entweder tätigen musst oder bekommen solltest.</p>
</article>
<div class="profilteil">
    <h2>Das bis du.</h2>
    <article class="du">
        <section>
            <img src="https://avatars2.githubusercontent.com/u/55796458?s=460&u=b145ad1f1b658eca4f227c5494dcb240d8e7f93a&v=4">
        </section>
        <section>
            <p><strong>Nutzername </strong><?php echo $du['name'] ?></p>
            <p><strong>Mail: </strong><?php echo $du['mail'] ?></p>
            <p><strong>Kontostand: </strong><?php echo round($du['value'], 1) ?> CHF</p>
        </section>
    </article>
</div>
<div class="profilteil">
    <h2>Das ist deine WG.</h2>
    <p><strong>WG-Name: </strong><?php echo $wg['name'] ?></p>
    <p><strong>Bewohner*innen: </strong><br>
        <?php foreach ($wgmitbewohner as $mensch) {
            echo $mensch['name'] . ', ';
        } ?></p>
</div>
<div class="profilteil">
    <h2>Deine offenen Zahlungen.</h2>
</div>
<?php include('template/foot.php') ?>