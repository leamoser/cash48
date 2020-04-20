<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>cash48 - dis wg-app des vertrauens</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/style.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/normalize.css">
    <link rel='shortcut icon' type='image/x-icon' href='icons/favicon.ico' />
    <!-- PWA -->
    <link rel='manifest' href='/manifest.json'>
    <link rel="apple-touch-icon" href="icons/icon-300x300.png">
    <!-- PWA -->
</head>
<?php
$angemeldetals = get_person_by_id($user_id);
?>

<body>
    <article class="overlay">
        <p class="menu" onclick="closeMenu()">> Zurück</p>
        <nav>
            <ul>
                <li><a href="payment.php">Zahlung eintragen</a></li>
                <li><a href="bookings.php">Aktuelle Zahlungen</a></li>
                <li><a href="overview.php">Übersicht</a></li>
                <li><a href="reset.php">Kassensturz machen</a></li>
                <li><a href="lastreset.php">Kassenstürze</a></li>
                <li><a href="profile.php">Dein Profil</a></li>
                <li><a href="index.php">Logout</a></li>
            </ul>
        </nav>
        <p class="menu">Angemeldet als <?php echo $angemeldetals['name'] ?></p>
    </article>
    <main>
        <?php if ($logged_in) { ?>
            <p class="menu" onclick="showMenu()">> Menu</p>
        <?php } ?>