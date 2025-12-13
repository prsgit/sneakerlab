<?php
require_once "../config/db.php";

if (!isset($_GET['id'])) {
    echo "Producto no válido";
    exit;
}

$id_producto = $_GET['id'];

$sql = "SELECT * FROM producto WHERE id_producto = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_producto]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    echo "Producto no encontrado";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $producto['nombre']; ?></title>
</head>
<body>

<h1><?php echo $producto['nombre']; ?></h1>

<p><?php echo $producto['descripcion']; ?></p>

<p>
    <strong>Precio:</strong>
    <?php echo $producto['precio']; ?> €
</p>

<?php if (!empty($producto['imagen_url'])): ?>
    <img 
        src="../assets/img/<?php echo $producto['imagen_url']; ?>" 
        alt="<?php echo $producto['nombre']; ?>" 
        width="300"
    >
<?php endif; ?>

</body>
</html>
