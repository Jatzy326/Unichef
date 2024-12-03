<?php
session_start();

if (isset($_GET['id_articulo'])) {
    $id_articulo = $_GET['id_articulo'];

    if (isset($_SESSION['cart'][$id_articulo])) {
        unset($_SESSION['cart'][$id_articulo]);
    }
}

header("Location: cart.php");
exit();
?>
