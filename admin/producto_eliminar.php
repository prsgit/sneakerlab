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

/* Comprobar que el producto existe y está activo */
$sql = "SELECT imagen_url FROM producto WHERE id_producto = ? AND activo = 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_producto]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    header("Location: productos.php");
    exit;
}

/* "Eliminar" producto de la BD = a desactivar para no romper el histórico ya que puede estra referenciado */
$sqlDelete = "UPDATE producto SET activo = 0 WHERE id_producto = ?";
$stmtDelete = $pdo->prepare($sqlDelete);
$stmtDelete->execute([$id_producto]);


/* Volver al listado */
header("Location: productos.php");
exit;
