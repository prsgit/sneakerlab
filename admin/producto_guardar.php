<?php
session_start();
require_once "../config/db.php";

/* Proteger el acceso: solo admin */
if (empty($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* 1) Comprobar que viene por POST */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: productos.php");
    exit;
}

/* 2) Recoger datos del formulario */
$nombre = $_POST["nombre"] ?? "";
$descripcion = $_POST["descripcion"] ?? "";
$precio = $_POST["precio"] ?? "";
$stock = $_POST["stock"] ?? "";

/* 3) Validación mínima */
if ($nombre === "" || $descripcion === "" || $precio === "" || $stock === "") {
    echo "Faltan datos obligatorios.";
    exit;
}

/* 4) Validar imagen */
if (!isset($_FILES["imagen"]) || $_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {
    echo "Error al subir la imagen.";
    exit;
}

/* 5) Preparar destino de la imagen */
$carpetaDestino = "../assets/img/";

/* Crear la carpeta si no existe (por seguridad) */
if (!is_dir($carpetaDestino)) {
    mkdir($carpetaDestino, 0755, true);
}

/* 6) Nombre final del archivo (evita colisiones) */
$extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
$nombreArchivo = uniqid("img_") . "." . $extension;

$rutaFinal = $carpetaDestino . $nombreArchivo;

/* 7) Mover el archivo subido a la carpeta destino */
if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaFinal)) {
    echo "No se pudo guardar la imagen en el servidor.";
    exit;
}

/* 8) Insertar producto en BD */
$sql = "INSERT INTO producto (nombre, descripcion, precio, stock, imagen_url)
        VALUES (?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$nombre, $descripcion, $precio, $stock, $nombreArchivo]);

/* 9) Volver al listado */
header("Location: productos.php");
exit;
