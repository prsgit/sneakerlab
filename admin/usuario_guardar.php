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

/* Validación */
if ($nombre === "" || $email === "" || $contrasena === "" || $rol === "") {
    echo "Faltan datos obligatorios.";
    exit;
}

/* Validar rol permitido */
if ($rol !== "cliente" && $rol !== "admin") {
    echo "Rol no válido.";
    exit;
}

/* Hashear contraseña */
$contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

/* Inserta usuario en la tabla */
$sql = "INSERT INTO usuario (nombre, email, telefono, direccion, contrasena, rol)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$nombre, $email, $telefono, $direccion, $contrasenaHash, $rol]);

/* Volver al listado */
header("Location: usuarios.php");
exit;
