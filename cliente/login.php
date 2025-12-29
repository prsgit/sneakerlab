<?php
session_start();
require_once "../config/db.php";

$error = "";

/* Si ya está logueado como cliente, al catálogo */
if (!empty($_SESSION["cliente"]) && !empty($_SESSION["id_usuario"])) {
    header("Location: catalogo.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $error = "Completa todos los campos.";
    } else {

        /* Buscar usuario por email y rol cliente */
        $stmt = $pdo->prepare("SELECT id_usuario, nombre, email, contrasena, rol FROM usuario WHERE email = ? AND rol = 'cliente' LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $error = "Credenciales incorrectas.";
        } else {

            if (!password_verify($password, $user["contrasena"])) {
                $error = "Credenciales incorrectas.";
            } else {

                /* Login */
                $_SESSION["cliente"] = true;
                $_SESSION["id_usuario"] = $user["id_usuario"];
                $_SESSION["rol"] = $user["rol"];
                $_SESSION["nombre"] = $user["nombre"];

                header("Location: catalogo.php");
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Cliente</title>
</head>
<body>

<h1>Login Cliente</h1>

<?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="post">
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Contraseña:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Entrar</button>
</form>

<p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>

</body>
</html>
