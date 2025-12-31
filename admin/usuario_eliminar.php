<?php
session_start();
require_once "../config/db.php";

/* acceso: solo admin */
if (empty($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* Validar ID */
if (!isset($_GET["id"])) {
    header("Location: usuarios.php");
    exit;
}

$id_usuario = (int)$_GET["id"];

/* Evitar que un admin se elimine a sí mismo */
if (isset($_SESSION["id_usuario"]) && $_SESSION["id_usuario"] == $id_usuario) {
    echo "No puedes eliminar tu propio usuario.";
    exit;
}

/* Comprobar que el usuario existe y está activo */
$stmt = $pdo->prepare("SELECT id_usuario FROM usuario WHERE id_usuario = ? AND activo = 1");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: usuarios.php");
    exit;
}

/* “Eliminar” usuario = desactivar */
$stmtDel = $pdo->prepare("UPDATE usuario SET activo = 0 WHERE id_usuario = ?");
$stmtDel->execute([$id_usuario]);

/* Volver al listado */
header("Location: usuarios.php");
exit;
