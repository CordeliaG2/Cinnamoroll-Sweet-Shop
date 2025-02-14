<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión - Cinnamoroll Sweet Shop</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts: Lobster -->
  <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
  <style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Lobster', cursive;
      /* Fondo animado */
      background: #ffe4e1;
    }
    /* Contenedor del fondo animado */
    .animated-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('cn.gif'); /* Reemplaza con la ruta de tu GIF */
      background-repeat: repeat;
      background-size: 70px 70px;
      animation: move-mosaic 35s linear infinite;
      z-index: 1;
    }
    @keyframes move-mosaic {
      0% { background-position: 100% 100%; }
      100% { background-position: 0% 0%; }
    }
    /* Contenedor de login */
    .login-card {
      background: rgba(255,255,255,0.95);
      border-radius: 15px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      padding: 30px;
      width: 100%;
      max-width: 400px;
      position: relative;
      z-index: 2;
    }
    .login-title {
      text-align: center;
      margin-bottom: 20px;
      color: #87CEEB;
      font-size: 28px;
    }
  </style>
</head>
<body>
  <div class="animated-background"></div>
  <div class="d-flex align-items-center justify-content-center" style="min-height: 100vh; position: relative; z-index: 2;">
    <div class="login-card">
      <h2 class="login-title">Iniciar Sesión</h2>
      <form action="procesar_login.php" method="POST">
        <div class="mb-3">
          <label for="correo" class="form-label">Correo electrónico</label>
          <input type="email" class="form-control" id="correo" name="correo" placeholder="Introduce tu correo" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Introduce tu contraseña" required>
        </div>
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </div>
      </form>
      <hr>
      <div class="d-grid gap-2 mt-3">
        <a href="registro.php" class="btn btn-secondary">Registrarse</a>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
