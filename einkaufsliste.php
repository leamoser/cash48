<?php
//Initialisierung der Session und anderen Dateien
session_start();
require_once('system/config.php');
require_once('system/data.php');
require_once('system/sessionhandler.php');

//Validierung Eintragen
$msg = '';
if (isset($_POST['produkt_eintragen'])) {
    $is_valid = true;
    if (!empty($_POST['produkt'])) {
        $produkt_name = $_POST['produkt'];
        $status_prod = 1;
    } else {
        $is_valid = false;
        $msg = 'Bitte trage zuerst ein Produkt ein.';
    }
    if ($is_valid) {
        insert_product($produkt_name, $wg_id, $status_prod, $user_id);
    }
    //Alle Produkte holen
}

//Validierung löschen
if (isset($_POST['delete_product'])) {
    $id_product = $_POST['product_id'];
    update_product_status($id_product);
}
?>


<?php include('template/head.php') ?>
<article class="intro">
    <h1>Deine<br>Einkaufsliste 🛒</h1>
    <p>Hier entsteht die WG-Einkaufsliste. Trage Produkte ein die gekauft werden müssen und streiche sie, wenn sie gekauft worden sind.</p>
</article>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <?php if (strlen($msg) != 0) { ?>
        <div class="error">
            <?php echo $msg; ?>
        </div>
    <?php } ?>
    <input type="text" name="produkt" id="produkt">
    <button id="produkt_eintragen" type="submit" name="produkt_eintragen" value="produkt_eintragen">Auf Einkaufsliste setzen</button>
</form>
<article class="einkaufsliste">
    <h2>Einkaufsliste</h2>
    <?php
    $open_products = get_products_by_wg($wg_id);
    $action = $_SERVER['PHP_SELF'];
    foreach ($open_products as $open_product) {
        echo '<div><p>' . $open_product['produkt'] . '</p><form action="' . $action . '" method="post"><input type="hidden" name="product_id" value="' . $open_product['id'] . '" id="product_id"><button type="submit" name="delete_product" class="delete_product">X</button></form></div>';
    }
    ?>
</article>
<?php include('template/foot.php') ?>