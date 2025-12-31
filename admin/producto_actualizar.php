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

/* Validar precio/stock */
if (!is_numeric($precio) || (float)$precio < 0) {
    echo "Precio inválido.";
    exit;
}
if (!is_numeric($stock) || (int)$stock < 0) {
    echo "Stock inválido.";
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

/* Si admin sube una nueva imagen, se procesa */
if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {

    /* Validar tamaño imagen*/
    $maxBytes = 3 * 1024 * 1024;
    if (!isset($_FILES["imagen"]["size"]) || (int)$_FILES["imagen"]["size"] <= 0 || (int)$_FILES["imagen"]["size"] > $maxBytes) {
        echo "La imagen supera el tamaño permitido (3MB).";
        exit;
    }

    /* Validar imagen*/
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

    $carpetaDestino = "../assets/img/";

    if (!is_dir($carpetaDestino)) {
        mkdir($carpetaDestino, 0755, true);
    }

    if (!is_writable($carpetaDestino)) {
        echo "La carpeta de imágenes no tiene permisos de escritura.";
        exit;
    }

    /* Nombre final del archivo */
    $extension = $allowed[$mime];
    $nombreArchivo = uniqid("img_", true) . "." . $extension;

    $rutaFinal = $carpetaDestino . $nombreArchivo;

    if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaFinal)) {
        echo "No se pudo guardar la nueva imagen.";
        exit;
    }

    /* Si se guardó la nueva imagen, borramos la vieja */
    if ($imagenActual) {
        // Evita nombres con rutas tipo "../"
        if (basename($imagenActual) === $imagenActual) {
            $rutaImagenVieja = $carpetaDestino . $imagenActual;

            $realDir = realpath($carpetaDestino);
            $realFile = realpath($rutaImagenVieja);

            // Solo borrar si el archivo está dentro de la carpeta destino
            if ($realDir && $realFile && str_starts_with($realFile, $realDir) && file_exists($rutaImagenVieja)) {
                unlink($rutaImagenVieja);
            }
        }
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
