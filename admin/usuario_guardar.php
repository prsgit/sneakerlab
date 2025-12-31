<?php
session_start();
require_once "../config/db.php";

/* solo admin */
if (empty($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* Solo POST */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: usuarios.php");
    exit;
}

/* Recoger datos del formulario */
$nombre = $_POST["nombre"] ?? "";
$email = $_POST["email"] ?? "";
$telefono = $_POST["telefono"] ?? "";
$direccion = $_POST["direccion"] ?? "";
$contrasena = $_POST["contrasena"] ?? "";
$rol = $_POST["rol"] ?? "";

/* Normalizar */
$nombre = trim($nombre);
$email = trim(strtolower($email));
$telefono = trim($telefono);
$direccion = trim($direccion);

/* Validación */
if ($nombre === "" || $email === "" || $contrasena === "" || $rol === "") {
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

/* Comprobar email duplicado */
$st = $pdo->prepare("SELECT 1 FROM usuario WHERE email = ? LIMIT 1");
$st->execute([$email]);
if ($st->fetchColumn()) {
    echo "Ya existe un usuario con ese email.";
    exit;
}

/* Hashear contraseña */
$contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

/* Inserta usuario en la tabla */
$sql = "INSERT INTO usuario (nombre, email, telefono, direccion, contrasena, rol)
        VALUES (?, ?, ?, ?, ?, ?)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $email, $telefono, $direccion, $contrasenaHash, $rol]);
} catch (Throwable $e) {
    echo "Error al guardar el usuario.";
    exit;
}

/* Volver al listado */
header("Location: usuarios.php");
exit;
