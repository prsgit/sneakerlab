<?php
session_start();
require_once "../config/db.php";

if (empty($_SESSION["cliente"]) || empty($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

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


<h1><?php echo $producto['nombre']; ?></h1>

<p><?php echo $producto['descripcion']; ?></p>

<p>
    <strong>Precio:</strong>
    <?php echo $producto['precio']; ?> €
</p>

<form method="post" action="add_to_cart.php">
    <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
    <button type="submit">Añadir al carrito</button>
</form>


<?php if (!empty($producto['imagen_url'])): ?>
    <img 
        src="../assets/img/<?php echo $producto['imagen_url']; ?>" 
        alt="<?php echo $producto['nombre']; ?>" 
        width="300"
    >
<?php endif; ?>

</body>
</html>
