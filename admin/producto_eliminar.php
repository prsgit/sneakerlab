<?php
session_start();
require_once "../config/db.php";

/* acceso: solo admin */
if (empty($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* Comprobar que viene un ID */
if (!isset($_GET['id'])) {
    header("Location: productos.php");
    exit;
}

$id_producto = $_GET['id'];

/* Obtener imagen del producto */
$sql = "SELECT imagen_url FROM producto WHERE id_producto = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_producto]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    header("Location: productos.php");
    exit;
}

/* Eliminar producto de la BD */
$sqlDelete = "DELETE FROM producto WHERE id_producto = ?";
$stmtDelete = $pdo->prepare($sqlDelete);
$stmtDelete->execute([$id_producto]);

/* Eliminar imagen del servidor */
$rutaImagen = "../assets/img/" . $producto['imagen_url'];

if (file_exists($rutaImagen)) {
    unlink($rutaImagen);
}

/* Volver al listado */
header("Location: productos.php");
exit;
