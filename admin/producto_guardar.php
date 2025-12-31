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

/* Normalizar */
$nombre = trim($nombre);
$descripcion = trim($descripcion);

/* Validación */
if ($nombre === "" || $descripcion === "" || $precio === "" || $stock === "") {
    echo "Faltan datos obligatorios.";
    exit;
}

/* Validar precio/stock (numéricos y no negativos) */
if (!is_numeric($precio) || (float)$precio < 0) {
    echo "Precio inválido.";
    exit;
}
if (!is_numeric($stock) || (int)$stock < 0) {
    echo "Stock inválido.";
    exit;
}

/* Validar imagen */
if (!isset($_FILES["imagen"]) || $_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {
    echo "Error al subir la imagen.";
    exit;
}

/* Validar tamaño máximo */
$maxBytes = 3 * 1024 * 1024;
if (!isset($_FILES["imagen"]["size"]) || (int)$_FILES["imagen"]["size"] <= 0 || (int)$_FILES["imagen"]["size"] > $maxBytes) {
    echo "La imagen supera el tamaño permitido (3MB).";
    exit;
}

/* Validar tipo */
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($_FILES["imagen"]["tmp_name"]);

$allowed = [
    "image/jpeg" => "jpg",
    "image/png"  => "png",
    "image/webp" => "webp",
];

if (!isset($allowed[$mime])) {
    echo "Formato de imagen no permitido. Solo JPG, PNG o WEBP.";
    exit;
}

/* Preparar destino de la imagen */
$carpetaDestino = "../assets/img/";

/* Crea la carpeta si no existe */
if (!is_dir($carpetaDestino) && !mkdir($carpetaDestino, 0755, true)) {
    echo "No se pudo crear la carpeta de imágenes.";
    exit;
}

/* Comprobar que es escribible */
if (!is_writable($carpetaDestino)) {
    echo "La carpeta de imágenes no tiene permisos de escritura.";
    exit;
}

/* Nombre final del archivo  */
$extension = $allowed[$mime];
$nombreArchivo = uniqid("img_", true) . "." . $extension;

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

try {
    $stmt->execute([$nombre, $descripcion, $precio, $stock, $nombreArchivo]);
} catch (Throwable $e) {
    if (file_exists($rutaFinal)) {
        unlink($rutaFinal);
    }
    echo "Error al guardar el producto.";
    exit;
}

/* Volver al listado */
header("Location: productos.php");
exit;
