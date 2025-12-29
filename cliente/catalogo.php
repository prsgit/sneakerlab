<?php
session_start();
require_once "../config/db.php";

if (empty($_SESSION["cliente"]) || empty($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT * FROM producto";
$stmt = $pdo->query($sql);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de productos</title>
</head>
<body>

<nav style="margin-bottom:20px; padding:10px; border-bottom:1px solid #ccc;">
    <strong>Bienvenido<?php echo isset($_SESSION['nombre']) ? ', ' . htmlspecialchars($_SESSION['nombre']) : ''; ?></strong>
    |
    <a href="catalogo.php">Catálogo</a>
    |
    <a href="carrito.php">Carrito</a>
    |
    <a href="mis_pedidos.php">Mis pedidos</a>
    |
    <a href="logout.php">Cerrar sesión</a>
</nav>



<h1>Catálogo de productos</h1>

<?php if (count($productos) === 0): ?>
    <p>No hay productos disponibles.</p>
<?php else: ?>
    <?php foreach ($productos as $producto): ?>
        <div>
            <h2><?php echo $producto['nombre']; ?></h2>

            <p>
                <strong>Precio:</strong>
                <?php echo $producto['precio']; ?> €
            </p>

            <p>
                <a href="detalle_producto.php?id=<?php echo $producto['id_producto']; ?>">
                    Ver detalle
                </a>
            </p>

            <?php if (!empty($producto['imagen_url'])): ?>
                <img 
                    src="../assets/img/<?php echo $producto['imagen_url']; ?>" 
                    alt="<?php echo $producto['nombre']; ?>" 
                    width="150"
                >
            <?php endif; ?>

        </div>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
