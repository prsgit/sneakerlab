<?php
session_start();

/* clientes logueados */
if (empty($_SESSION["cliente"]) || empty($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

/* Vaciar carrito */
unset($_SESSION["carrito"]);

/* Volver al carrito */
header("Location: carrito.php");
exit;
