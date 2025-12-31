<?php
session_start();
require_once "../config/db.php";

/* Solo POST */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: registro.php");
    exit;
}

/* Recoger datos */
$nombre = $_POST["nombre"] ?? "";
$email = $_POST["email"] ?? "";
$telefono = $_POST["telefono"] ?? "";
$direccion = $_POST["direccion"] ?? "";
$password = $_POST["password"] ?? "";

/* Normalizar */
$nombre = trim($nombre);
$email = trim(strtolower($email));
$telefono = trim($telefono);
$direccion = trim($direccion);

/* Validación */
if ($nombre === "" || $email === "" || $password === "") {
    echo "Faltan datos obligatorios.";
    exit;
}

/* Validar email */
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Email no válido.";
    exit;
}

/* Validar contraseña (mínimo) */
if (strlen($password) < 8) {
    echo "La contraseña debe tener al menos 8 caracteres.";
    exit;
}

/* Comprobar si el email ya existe */
$stmt = $pdo->prepare("SELECT id_usuario FROM usuario WHERE email = ? LIMIT 1");
$stmt->execute([$email]);

if ($stmt->fetch()) {
    echo "El email ya está registrado.";
    exit;
}

/* Hashear contraseña */
$hash = password_hash($password, PASSWORD_DEFAULT);

/* Insertar usuario como cliente */
$sql = "INSERT INTO usuario (nombre, email, telefono, direccion, contrasena, rol)
        VALUES (?, ?, ?, ?, ?, 'cliente')";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([$nombre, $email, $telefono, $direccion, $hash]);
} catch (Throwable $e) {
    echo "Error al registrar el usuario.";
    exit;
}

/* Redirigir al login */
header("Location: login.php");
exit;
