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
    header("Location: productos.php");
    exit;
}

/* Recoger datos */
$id_producto = $_POST["id_producto"] ?? "";
$nombre = $_POST["nombre"] ?? "";
$descripcion = $_POST["descripcion"] ?? "";
$precio = $_POST["precio"] ?? "";
$stock = $_POST["stock"] ?? "";

/* Validación  */
if ($id_producto === "" || $nombre === "" || $descripcion === "" || $precio === "" || $stock === "") {
    echo "Faltan datos obligatorios.";
    exit;
}

/* Obtener producto actual (para saber imagen actual) */
$stmt = $pdo->prepare("SELECT imagen_url FROM producto WHERE id_producto = ?");
$stmt->execute([$id_producto]);
$actual = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$actual) {
    echo "Producto no encontrado.";
    exit;
}

$imagenActual = $actual["imagen_url"];
$nuevaImagenNombre = $imagenActual;

/* Si el admin sube una nueva imagen, la procesamos */
if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {

    $carpetaDestino = "../assets/img/";

    if (!is_dir($carpetaDestino)) {
        mkdir($carpetaDestino, 0755, true);
    }

    $extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
    $nombreArchivo = uniqid("img_") . "." . $extension;

    $rutaFinal = $carpetaDestino . $nombreArchivo;

    if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaFinal)) {
        echo "No se pudo guardar la nueva imagen.";
        exit;
    }

    /* Si se guardó la nueva imagen, borramos la vieja */
    $rutaImagenVieja = $carpetaDestino . $imagenActual;
    if ($imagenActual && file_exists($rutaImagenVieja)) {
        unlink($rutaImagenVieja);
    }

    $nuevaImagenNombre = $nombreArchivo;
}

/* Actualizar producto */
$sql = "UPDATE producto
        SET nombre = ?, descripcion = ?, precio = ?, stock = ?, imagen_url = ?
        WHERE id_producto = ?";

$stmtUp = $pdo->prepare($sql);
$stmtUp->execute([$nombre, $descripcion, $precio, $stock, $nuevaImagenNombre, $id_producto]);

/* Volver al listado */
header("Location: productos.php");
exit;
