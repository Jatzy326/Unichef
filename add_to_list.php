<?php
session_start();

if (isset($_POST['add_to_list'])) {
    $id_articulo = $_POST['id_articulo'];
    $quantity = $_POST['quantity'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (isset($_SESSION['cart'][$id_articulo])) {
        $_SESSION['cart'][$id_articulo]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$id_articulo] = array(
            'product_id' => $id_articulo,
            'quantity' => $quantity
        );
    }

    header("Location: rejilla.php");
    exit();
}
?>
