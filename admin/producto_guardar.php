<?php
session_start();
require_once "../config/db.php";

/* acceso: solo admin */
if (empty($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* Comprobar que viene por POST */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: productos.php");
    exit;
}

/* Recoger datos del formulario */
$nombre = $_POST["nombre"] ?? "";
$descripcion = $_POST["descripcion"] ?? "";
$precio = $_POST["precio"] ?? "";
$stock = $_POST["stock"] ?? "";

/* Validación */
if ($nombre === "" || $descripcion === "" || $precio === "" || $stock === "") {
    echo "Faltan datos obligatorios.";
    exit;
}

/* Validar imagen */
if (!isset($_FILES["imagen"]) || $_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {
    echo "Error al subir la imagen.";
    exit;
}

/* Preparar destino de la imagen */
$carpetaDestino = "../assets/img/";

/* Crea la carpeta si no existe */
if (!is_dir($carpetaDestino)) {
    mkdir($carpetaDestino, 0755, true);
}

/* Nombre final del archivo */
$extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
$nombreArchivo = uniqid("img_") . "." . $extension;

$rutaFinal = $carpetaDestino . $nombreArchivo;

/* Mover el archivo subido a la carpeta destino */
if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaFinal)) {
    echo "No se pudo guardar la imagen en el servidor.";
    exit;
}

/* Insertar producto en BD */
$sql = "INSERT INTO producto (nombre, descripcion, precio, stock, imagen_url)
        VALUES (?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$nombre, $descripcion, $precio, $stock, $nombreArchivo]);

/* Volver al listado */
header("Location: productos.php");
exit;
