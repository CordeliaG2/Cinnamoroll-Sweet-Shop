<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantalla de Login</title>
    <!-- Enlace a Bootstrap para estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <style>
        .animated-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('cn.gif'); /* Ruta del GIF */
            background-repeat: repeat;
            background-size: 70px 70px; /* Tamaño uniforme del mosaico */
            animation: move-mosaic 60s linear infinite; /* Animación del mosaico */
            z-index: -1; /* Coloca el fondo detrás del contenido */
        }
        body, html {
       height: 100%;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden; /* Evita barras de desplazamiento */
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: white; /* Fondo blanco estático */
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1; /* Asegura que esté por encima del fondo animado */
        }

        .login-title {
            font-family: 'Lobster', cursive;
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #87CEEB; /* Azul cielo */
        }
        @keyframes move-mosaic {
            0% {
                background-position: 100% 100%; /* Esquina inferior derecha */
            }
            100% {
                background-position: 30% 0%; /* Esquina superior izquierda */
            }
        }

    </style>
</head>
<body>
    <!-- Contenedor del fondo animado -->
    <div class="animated-background"></div>

    <!-- Contenedor del formulario de login -->
    <div class="login-container">
        <h2 class="text-center">Iniciar Sesión</h2>
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
</body>
</html>
