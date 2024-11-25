<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantalla de Login</title>
    <!-- Enlace a Bootstrap para estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa; /* Color de fondo suave */
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .login-title {
            font-family: 'Lobster', cursive;
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #87CEEB; /* Azul cielo */
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2 class="login-title">Iniciar Sesión</h2>
    <form action="procesar_login.php" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Introduce tu correo" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Introduce tu contraseña" required>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </div>
    </form>
</div>

</body>
</html>
