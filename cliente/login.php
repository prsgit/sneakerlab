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

    $email = trim(strtolower($email));

    if ($email === "" || $password === "") {
        $error = "Completa todos los campos.";
    }else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Credenciales incorrectas.";
    } else {

        /* Buscar usuario por email y rol cliente */
        $stmt = $pdo->prepare("SELECT id_usuario, nombre, email, contrasena, rol FROM usuario WHERE email = ? AND rol = 'cliente' AND activo = 1 LIMIT 1");
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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Cliente</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Fuente -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/theme.css">

</head>

<body>
  <main class="auth-wrap">
    <section class="auth-card" aria-label="Login Cliente">
      <div class="auth-header">
        <span class="auth-badge">
          <span style="width:8px;height:8px;border-radius:50%;background:var(--brand);display:inline-block;"></span>
          SNEAKERLAB
        </span>
        <h1 class="auth-title">Inicia sesión</h1>
      </div>

      <div class="auth-body">
        <?php if (!empty($error)): ?>
          <div class="alert-soft" role="alert">
            <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>

        <form method="post" novalidate>
          <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input class="form-control" type="email" id="email" name="email" required autocomplete="email" placeholder="tuemail@ejemplo.com">
          </div>

          <div class="mb-3">
            <label class="form-label" for="password">Contraseña</label>
            <input class="form-control" type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••">
          </div>

          <button class="btn btn-brand w-100" type="submit">Entrar</button>
        </form>
      </div>

      <div class="auth-footer">
        ¿No tienes cuenta?
        <a class="auth-link" href="registro.php">Regístrate</a>
      </div>
    </section>
  </main>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
