<?php
//Initialisierung der Session und anderen Dateien
session_start();
require_once('system/config.php');
require_once('system/data.php');
require_once('system/sessionhandler.php');

//Validierung Eintragen
$msg = '';
if (isset($_POST['traum_eintragen'])) {
    $is_valid = true;
    if (!empty($_POST['traum'])) {
        $traum_desc = $_POST['traum'];
    } else {
        $is_valid = false;
        $msg = 'Bitte trage zuerst einen Traum ein.';
    }
    if ($is_valid) {
        insert_traum($traum_desc, $user_id, $wg_id);
    }
}
?>


<?php include('template/head.php') ?>
    <article class="intro">
        <h1>Eure<br>Träume 🛏️</h1>
        <p>Hier werden alle Träume, die Personen in deiner WG hatten erfasst.</p>
    </article>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <?php if (strlen($msg) != 0) { ?>
            <div class="error">
                <?php echo $msg; ?>
            </div>
        <?php } ?>
        <textarea name="traum" id="traum" rows="5"></textarea>
        <button id="traum_eintragen" type="submit" name="traum_eintragen" value="traum_eintragen">Traum eintragen</button>
    </form>
    <article class="traume">
        <h2>Alle Träume</h2>
        <?php
        $all_dreams = get_dreams_by_wg($wg_id);
        foreach ($all_dreams as $dream) {
            //var_dump($dream);
            $dreamdate = new DateTime($dream['date']);
            $user = get_person_by_id($dream['user']);
            echo '<div><h3>' . $user['name'] . ', ' . $dreamdate->format('d. F Y, G:i') . ' </h3><p>' . $dream['description'] . '</p></div>';
        }
        ?>
    </article>
<?php include('template/foot.php') ?>