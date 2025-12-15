<?php
session_start();

if (!isset($_POST['id_producto'])) {
    header("Location: catalogo.php");
    exit;
}

$id_producto = $_POST['id_producto'];

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if (isset($_SESSION['carrito'][$id_producto])) {
    $_SESSION['carrito'][$id_producto]++;
} else {
    $_SESSION['carrito'][$id_producto] = 1;
}

header("Location: carrito.php");
exit;
