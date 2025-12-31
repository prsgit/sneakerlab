<?php
session_start();
require_once "../config/db.php";

$error = "";

/* Si ya está logueado como admin, al panel */
if (!empty($_SESSION["admin"]) && !empty($_SESSION["id_usuario"])) {
    header("Location: panel.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $error = "Completa todos los campos.";
    } else {

        /* Buscar usuario por email y rol admin */
        $stmt = $pdo->prepare("SELECT id_usuario, nombre, email, contrasena, rol FROM usuario WHERE email = ? AND rol = 'admin' AND activo = 1 LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $error = "Credenciales incorrectas.";
        } else {

            /* Verificar contraseña (hash) */
            if (!password_verify($password, $user["contrasena"])) {
                $error = "Credenciales incorrectas.";
            } else {

                /* Login */
                $_SESSION["admin"] = true;
                $_SESSION["id_usuario"] = $user["id_usuario"];
                $_SESSION["rol"] = $user["rol"];
                $_SESSION["nombre"] = $user["nombre"];

                header("Location: panel.php");
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
  <title>Login Admin</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/theme.css">

  <style>
    .auth-badge { background: rgba(15, 23, 42, .08); }
  </style>

</head>

<body>
  <main class="auth-wrap">
    <section class="auth-card" aria-label="Login Admin">
      <div class="auth-header">
        <span class="auth-badge">
          <span style="width:8px;height:8px;border-radius:50%;background:var(--brand);display:inline-block;"></span>
          Administración SNEAKERLAB
        </span>
        <h1 class="auth-title">Acceso admin</h1>
      </div>

      <div class="auth-body">
        <?php if ($error): ?>
          <div class="alert-soft" role="alert">
            <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>

        <form method="post" novalidate>
          <div class="mb-3">
            <label class="form-label">Email</label><br>
            <input class="form-control" type="email" name="email" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Contraseña</label><br>
            <input class="form-control" type="password" name="password" required>
          </div>

          <button class="btn btn-brand w-100" type="submit">Entrar</button>
        </form>
      </div>

      <div class="auth-footer">
        <span style="color: var(--muted);">Acceso restringido, exclusivo para administradores</span>
      </div>
    </section>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
