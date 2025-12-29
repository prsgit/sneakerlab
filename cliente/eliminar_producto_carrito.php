<?php
session_start();

/* Solo clientes logueados */
if (empty($_SESSION["cliente"]) || empty($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

/* Validar id_producto */
if (!isset($_GET["id"])) {
    header("Location: carrito.php");
    exit;
}

$id_producto = (int)$_GET["id"];

/* Eliminar solo ese producto del carrito */
if (isset($_SESSION["carrito"][$id_producto])) {
    unset($_SESSION["carrito"][$id_producto]);
}

/* Volver al carrito */
header("Location: carrito.php");
exit;
