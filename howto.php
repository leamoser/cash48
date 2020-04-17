<?php
//Initialisierung der Session und anderen Dateien
session_start();
require_once('system/config.php');
require_once('system/data.php');
//Wenn eine Session vorhanden, dann ausloggen (abmelden)
if (isset($_SESSION['userid'])) {
    unset($_SESSION['userid']);
    session_destroy();
}
?>

<?php include('template/head.php') ?>
<a href="javascript:goBack()">-> zurück</a>
<article class="intro">
    <h1>was ist <br>cash48💰?</h1>
    <p>Deine WG-App des Vertrauen. Mit dieser App kannst du die Finanzen in deiner WG im Überblick behalten. </p>
</article>
<article class="intro">
    <h1>was kann <br>cash48💰?</h1>
    <ul>
        <li>Du kannst deine eigene WG registrieren</li>
        <li>Du kannst deine Mitbewohner*innen in deine WG einladen</li>
        <li>Alle WG-Bewohner*innen können ihre Zahlungen erfassen.</li>
        <li>Du kannst wann du willst Abrechnungen machen</li>
        <li>Du behältst den Überblick über offene Zahlungen, die du entweder noch tätigen musst oder noch erhalten sollst.</li>
    </ul>
    <p><strong>Kurzum: </strong> cash48 kann deine WG-Finanzen managen.</p>
</article>
<article class="intro">
    <h1>wie geht<br>cash48💰?</h1>
    <p>Registriere deine WG, lade deine Mitbewohner*innen ein, logget euch ein und schon könnt ihr beginnen Zahlungen zu erfassen. </p>
    <p>Mit einem Klick in "Kassensturz machen" kannst du eine Abrechnung erstellen. </p>
    <p>In deinem Profil siehst du offene Zahlungen. Mit einem Klick kannst du diese als bezahlt oder empfangen markieren. </p>
</article>
<article class="intro">
    <h1>wie weiter mit<br>cash48💰?</h1>
    <a href="/index.php">-> Einloggen</a><br>
    <a href="/registerwg.php">-> neue WG registrieren</a><br>
    <a href="/register.php">-> für bestehende WG registrieren.</a>
</article>
<?php include('template/foot.php') ?>