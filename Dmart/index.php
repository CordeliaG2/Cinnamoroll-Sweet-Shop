<?php
// Inicia la sesión
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php"); // Redirige al login si no está autenticado
    exit;
}

// Incluye los archivos de configuración de la base de datos y las constantes
require 'config/database.php';
require 'config/config.php';

// Conexión a la base de datos
$db = new Database();
$con = $db->conectar();

// Obtiene el rol del usuario autenticado (cliente o admin)
$id_usuario = $_SESSION['id_usuario'];
$sql = $con->prepare("SELECT rol FROM usuarios WHERE id_usuario = ?");
$sql->execute([$id_usuario]);
$usuario = $sql->fetch(PDO::FETCH_ASSOC);

// Prepara la consulta SQL para obtener productos activos
$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cinnamoroll Sweet Shop</title>

    <!-- Fuentes y Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
        /* Define el estilo para la fuente global y elimina márgenes */
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Lobster', cursive; /* Usa la fuente Lobster */
            background-color: #ffe4e1;
        }
        .card-img-top {
            height: 400px; /* Ajusta según el diseño */
            object-fit: cover; /* Recorta proporcionalmente las imágenes */
            width: 400%; /* Asegura que cubran todo el ancho de la tarjeta */
        }


        /* Fondo animado con gradiente de colores */
        .animated-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(300deg, #87CEEB, #6DD5ED, #5333ED); /* Degradado de azul */
            background-size: 600% 600%; /* Hace que el gradiente se mueva suavemente */
            animation: gradient-animation 10s ease infinite; /* Animación continua del fondo */
            z-index: -1; /* Coloca el fondo detrás del contenido */
        }

        /* Animación de gradiente */
        @keyframes gradient-animation {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        /* Estilos para el contenido (texto, etc.) */
        .content {
            z-index: 3;
            position: relative;
            color: black;
            text-align: center;
            padding: 20px;
            margin-top: 2px;
        }

        /* Estilos del header con fondo azul cielo */
        .bg-sky-blue {
            background-color: #87CEEB;
        }

        /* Animación de oscilación de la imagen */
        @keyframes swing {
            0% {
                transform: rotateX(0);
            }
            50% {
                transform: rotateX(40deg);
            }
            100% {
                transform: rotateX(0);
            }
        }

        /* Fuente personalizada para el texto del header */
        .custom-font {
            font-family: 'Lobster', cursive;
            font-size: 24px;
            color: white;
        }

        /* Aplicar la animación de oscilación a la imagen */
        .swing-image {
            animation: swing 1s ease-in-out infinite;
            height: 75px;
            transform-origin: bottom;
        }

        /* Oscilación de los botones al pasar el cursor por encima */
        @keyframes button-swing {
            0% {
                transform: translateX(0);
            }
            50% {
                transform: translateX(5px);
            }
            100% {
                transform: translateX(0);
            }
        }

        /* Aplica la animación a los botones cuando el cursor pasa sobre ellos */
        .btn:hover {
            animation: button-swing 0.5s ease-in-out infinite;
        }
    </style>
</head>

<body>
    <!-- Fondo animado 
    <div class="animated-background"></div>
    -->

    <!-- Encabezado -->
    <header class="navbar navbar-expand-lg navbar-dark bg-sky-blue shadow-sm">
        <div class="container">
            <a href="#" class="navbar-brand">
                <img src="cinnamoroll.png" alt="Cinnamoroll" class="swing-image">
                <strong class="custom-font swing-image">Cinnamoroll Sweet Shop</strong>
            </a>
            <div class="collapse navbar-collapse" id="navbarHeader">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!--    <li class="nav-item"><a href="#" class="nav-link active">Catálogo</a></li>
                    <li class="nav-item"><a href="#" class="nav-link active">Contacto</a></li>-->
                </ul>
                <div>
                    <?php if ($usuario['rol'] === 'admin') { ?>
                        <a href="admin_productos.php" class="btn btn-success">Administrar Productos</a>
                    <?php } ?>
                    <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Cuerpo principal -->
    <main class="container content">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <?php foreach ($resultado as $row) { ?>
                <div class="col">
                    <div class="card shadow-sm h-100">
                        <?php 
                        $id = $row['id'];
                        $image = "images/productos/$id/principal.png";
                        if (!file_exists($image)) $image = "images/no-photo.png";
                        ?>
                        <img src="<?php echo $image; ?>" class="card-img-top img-fluid rounded d-block w-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['nombre']; ?></h5>
                            <p class="card-text">$<?php echo number_format($row['precio'], 2, '.', ','); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="detalles.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>" class="btn btn-primary">Detalles</a>
                                <a href="#" class="btn btn-success">Agregar</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>
<script>
    const music = document.getElementById('backgroundMusic');
    const toggleButton = document.getElementById('toggleMusicButton');

    // Cambiar el estado de reproducción de la música al hacer clic
    toggleButton.addEventListener('click', () => {
        if (music.paused) {
            music.play();
            toggleButton.textContent = 'Apagar Música';
        } else {
            music.pause();
            toggleButton.textContent = 'Encender Música';
        }
    });
</script>
<audio id="backgroundMusic" autoplay loop>
    <source src="audio/Industrial Fields.mp3" type="audio/mpeg">
    Tu navegador no soporta la reproducción de audio.
</audio>

<!-- Botón para encender/apagar la música -->
<div class="text-center mt-3">
    <button id="toggleMusicButton" class="btn btn-primary">Apagar Música</button>
</div>

</body>
</html>
