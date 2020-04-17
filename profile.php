<?php
//Initialisierung der Session und anderen Dateien
session_start();
require_once('system/config.php');
require_once('system/data.php');
require_once('system/sessionhandler.php');

if (isset($_POST['zahlung_bezahlt'])) {
    update_status_bezahlt_by_reset_zahlung_id($_POST['reset_zahlung_id']);
    echo "<div id='erfolg' class='erfolg'><p>Die Zahlung wurde als bezahlt markiert.</p><p onclick='verschwinden()'>X</p></div>";
}
if (isset($_POST['zahlung_empfangen'])) {
    update_status_empfangen_by_reset_zahlung_id($_POST['reset_zahlung_id']);
    echo "<div id='erfolg' class='erfolg'><p>Die Zahlung wurde als empfangen markiert</p><p onclick='verschwinden()'>X</p></div>";
}
if (isset($_POST['profilbild_upload'])) {
    //Starteinstellungen
    $upload_folder = 'uploads/files/'; //Das Upload-Verzeichnis
    $filename = pathinfo($_FILES['datei']['name'], PATHINFO_FILENAME);
    $extension = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));
    //Überprüfen der Dateiendungen
    $allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
    if (!in_array($extension, $allowed_extensions)) {
        die("Ungültige Dateiendung. Nur png, jpg, jpeg und gif-Dateien sind erlaubt");
    }
    //Überprüfung der Dateigröße
    $max_size = 1500 * 1024; //1500 KB
    if ($_FILES['datei']['size'] > $max_size) {
        die("Bitte keine Dateien größer 500kb hochladen");
    }
    //Überprüfung dass das Bild keine Fehler enthält
    if (function_exists('exif_imagetype')) { //exif_imagetype erfordert die exif-Erweiterung
        $allowed_types = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
        $detected_type = exif_imagetype($_FILES['datei']['tmp_name']);
        if (!in_array($detected_type, $allowed_types)) {
            die("Nur der Upload von Bilddateien ist gestattet");
        }
    }
    //Pfad zum Upload
    $new_path = $upload_folder . $filename . '.' . $extension;
    $dateiname = $filename . '.' . $extension;
    //Neuer Dateiname falls die Datei bereits existiert
    if (file_exists($new_path)) { //Falls Datei existiert, hänge eine Zahl an den Dateinamen
        $id = 1;
        do {
            $new_path = $upload_folder . $filename . '_' . $id . '.' . $extension;
            $dateiname = $filename . '_' . $id . '.' . $extension;
            $id++;
        } while (file_exists($new_path));
    }
    //Alles okay, verschiebe Datei an neuen Pfad
    move_uploaded_file($_FILES['datei']['tmp_name'], $new_path);
    profilbild_updaten($dateiname, $user_id);
}

$du = get_person_by_id($user_id);
$wg = get_wg_by_id($du['wg']);
$wgmitbewohner = get_persons_by_wg($wg_id);
$zahlen = get_offene_zahlungen_by_user_id($user_id);
$empfangen = get_offene_empfaenge_by_user_id($user_id);

?>


<?php include('template/head.php') ?>
<article class="intro">
    <h1><?php echo $du['name'] ?>'s<br> Profil.</h1>
    <p>Hier siehst du alle Angaben zu dir, deiner WG und den Zahlungen die du entweder tätigen musst oder bekommen solltest.</p>
</article>
<div class="profilteil">
    <h2>Das bist du.</h2>
    <article class="du">
        <section>
            <img src="/uploads/files/<?php echo $du['profilbild'] ?>">
        </section>
        <section>
            <p><strong>Nutzername </strong><?php echo $du['name'] ?></p>
            <p><strong>Mail: </strong><?php echo $du['mail'] ?></p>
            <p><strong>Kontostand: </strong><?php echo round($du['value'], 1) ?> CHF</p>
        </section>
    </article>
    <?php if ($du['profilbild'] == "default.jpeg") { ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <button class="small"><input type="file" name="datei"></button><br>
            <button class="upload" type="submit" name="profilbild_upload" value="profilbild_upload">Profilbild hochladen</button>
        </form>
    <?php } ?>
</div>
<div class="profilteil">
    <h2>Das ist deine WG.</h2>
    <p><strong>WG-Name: </strong><?php echo $wg['name'] ?></p>
    <p><strong>Bewohner*innen: </strong><br>
        <?php foreach ($wgmitbewohner as $mensch) {
            echo $mensch['name'] . ', ';
        } ?></p>
</div>
<!-- Profilteil GELD ZAHLEN -->
<div class="profilteil">
    <h2>Du musst noch Zahlen...</h2>
    <?php
    if (!empty($zahlen)) {
        foreach ($zahlen as $zahlung) {
            $empfaenger = get_person_by_id($zahlung['empfaenger']);
            ?>
            <article class="resetbox">
                <p class="satz"><strong><?php echo "Du musst " . $empfaenger['name'] . " " . $zahlung['betrag'] . " CHF bezahlen."  ?></strong></p>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="hidden" name="reset_zahlung_id" value="<?php echo $zahlung['id'] ?>">
                    <button class="reset_button" type="submit" name="zahlung_bezahlt" value="zahlung_bezahlt">Ich habe bezahlt.</button>
                </form>
            </article>
        <?php }
} else { ?>
        <p>Du musst im Moment niemandem mehr etwas bezahlen.</p>
    <?php } ?>
</div>
<!-- Profilteil GELD EMPFANGEN -->
<div class="profilteil">
    <h2>Du bekommst noch Geld...</h2>
    <?php
    if (!empty($empfangen)) {
        foreach ($empfangen as $empfang) {
            $zahler = get_person_by_id($empfang['zahler']);
            ?>
            <article class="resetbox">
                <p class="satz"><strong><?php echo "Du bekommst von " . $zahler['name'] . " " . $empfang['betrag'] . " CHF."  ?></strong></p>
                <div class="stati">
                    <p>Hat <?php echo $zahler['name'] ?> schon bezahlt?</p>
                    <div value="<?php echo $empfang['bezahlt'] ?>" class="status"></div>
                </div>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="hidden" name="reset_zahlung_id" value="<?php echo $empfang['id'] ?>">
                    <button class="reset_button" type="submit" name="zahlung_empfangen" value="zahlung_empfangen">Geld bekommen.</button>
                </form>
            </article>
        <?php }
} else { ?>
        <p>Du bekommst von niemandem mehr Geld.</p>
    <?php } ?>
</div>
<?php include('template/foot.php') ?>