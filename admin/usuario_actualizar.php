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
$nuevaPassword = $_POST["contrasena"] ?? ""; // opcional

/* Normalizar */
$nombre = trim($nombre);
$email = trim(strtolower($email));
$telefono = trim($telefono);
$direccion = trim($direccion);

/* Validación */
if ($id_usuario === "" || $nombre === "" || $email === "" || $rol === "") {
    echo "Faltan datos obligatorios.";
    exit;
}

/* Validar email */
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Email no válido.";
    exit;
}

/* Validar rol permitido */
if ($rol !== "cliente" && $rol !== "admin") {
    echo "Rol no válido.";
    exit;
}

/* Comprobar que el usuario existe */
$stUser = $pdo->prepare("SELECT 1 FROM usuario WHERE id_usuario = ? LIMIT 1");
$stUser->execute([$id_usuario]);
if (!$stUser->fetchColumn()) {
    echo "Usuario no encontrado.";
    exit;
}

/* Evitar email duplicado en otro usuario */
$stDup = $pdo->prepare("SELECT 1 FROM usuario WHERE email = ? AND id_usuario <> ? LIMIT 1");
$stDup->execute([$email, $id_usuario]);
if ($stDup->fetchColumn()) {
    echo "Ya existe otro usuario con ese email.";
    exit;
}

/*
  Si la contraseña viene vacía:
    - actualizamos todo menos contraseña
  Si la contraseña viene con valor:
    - la hasheamos y actualizamos también contraseña
*/

try {
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
} catch (Throwable $e) {
    echo "Error al actualizar el usuario.";
    exit;
}

/* Volver al listado */
header("Location: usuarios.php");
exit;
