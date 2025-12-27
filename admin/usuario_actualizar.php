<?php
session_start();
require_once "../config/db.php";

/* acceso: solo admin */
if (empty($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* Solo POST */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: usuarios.php");
    exit;
}

/* Recoger datos */
$id_usuario = $_POST["id_usuario"] ?? "";
$nombre = $_POST["nombre"] ?? "";
$email = $_POST["email"] ?? "";
$telefono = $_POST["telefono"] ?? "";
$direccion = $_POST["direccion"] ?? "";
$rol = $_POST["rol"] ?? "";
$nuevaPassword = $_POST["password"] ?? ""; // opcional

/* Validación */
if ($id_usuario === "" || $nombre === "" || $email === "" || $rol === "") {
    echo "Faltan datos obligatorios.";
    exit;
}

/* Validar rol permitido */
if ($rol !== "cliente" && $rol !== "admin") {
    echo "Rol no válido.";
    exit;
}

/*
  Si la contraseña viene vacía:
    - actualizamos todo menos contraseña
  Si la contraseña viene con valor:
    - la hasheamos y actualizamos también contraseña
*/

if ($nuevaPassword === "") {

    $sql = "UPDATE usuario
            SET nombre = ?, email = ?, telefono = ?, direccion = ?, rol = ?
            WHERE id_usuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $email, $telefono, $direccion, $rol, $id_usuario]);

} else {

    $hash = password_hash($nuevaPassword, PASSWORD_DEFAULT);

    $sql = "UPDATE usuario
            SET nombre = ?, email = ?, telefono = ?, direccion = ?, rol = ?, contrasena = ?
            WHERE id_usuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $email, $telefono, $direccion, $rol, $hash, $id_usuario]);
}

/* Volver al listado */
header("Location: usuarios.php");
exit;
