<?php 
session_start();
require 'config/database.php';
require 'config/config.php';

$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

// Verifica si el usuario es admin (para mostrar botón "Agregar Producto")
$es_admin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cinnamoroll Sweet Shop</title>
  
  <!-- Google Fonts y Bootstrap CSS -->
  <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <link rel="stylesheet" type="text/css" href="css/estilos.css">
  
  <style>
    /* 🔹 FONDO DEGRADADO ANIMADO */
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Lobster', cursive;
      background: linear-gradient(-45deg, #ffdde1, #ee9ca7, #ffdde1, #ffebf7);
      background-size: 400% 400%;
      animation: gradientBG 10s ease infinite;
      overflow: hidden; /* Bloquea el scroll hasta que termine la animación */
    }
    @keyframes gradientBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* 🔹 LOGO INICIAL ANIMADO (AHORA COMPLETO) */
    #intro-logo {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 300px;
      height: auto;
      z-index: 1000;
      animation: logoIntro 3s forwards;
    }
    @keyframes logoIntro {
      0% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
      100% { opacity: 0; transform: translate(-50%, -50%) scale(10); }
    }

    /* 🔹 MARCA DE AGUA EN EL FONDO (NO INTERFIERE CON EL SCROLL) */
    #watermark {
      position: fixed;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 300px;
      height: auto;
      opacity: 0.1; /* Baja opacidad para que no interfiera con el contenido */
      z-index: -1;
    }

    /* 🔹 NAVBAR Y BOTÓN DE ADMIN (OCULTOS INICIALMENTE) */
    #main-navbar, #admin-btn {
      opacity: 0;
      transform: translateY(-50px);
      transition: opacity 1s ease-out, transform 1s ease-out;
    }

    /* 🔹 CONTENIDO PRINCIPAL */
    #contenido {
      opacity: 0;
      transform: scale(0.9);
      transition: opacity 1s ease-out, transform 1s ease-out;
    }
    .mostrar {
      opacity: 1 !important;
      transform: scale(1) !important;
    }

    /* 🔹 HEADER */
    .bg-sky-blue {
      background-color: #87CEEB;
    }
    .custom-font {
      color: white;
      font-size: 24px;
    }

    /* 🔹 BOTÓN DE MÚSICA */
    #toggleMusicButton {
      margin-left: 15px;
    }
  </style>
</head>
<body>
  <!-- 🔹 ANIMACIÓN INICIAL -->
  <img id="intro-logo" src="cinnamoroll.png" alt="Cinnamoroll Logo">

  <!-- 🔹 MARCA DE AGUA (LOGO FIJO EN EL FONDO) -->
  <img id="watermark" src="cinnamoroll.png" alt="Cinnamoroll Watermark">

  <!-- 🔹 HEADER (OCULTO INICIALMENTE) -->
  <header id="main-navbar" class="navbar navbar-expand-lg navbar-dark bg-sky-blue shadow-sm">
    <div class="container">
      <a href="#" class="navbar-brand">
        <strong class="custom-font">Cinnamoroll Sweet Shop</strong>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarHeader">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a href="#" class="nav-link active">Catálogo</a></li>
          <li class="nav-item"><a href="#" class="nav-link active">Contacto</a></li>
        </ul>
        <button id="toggleMusicButton" class="btn btn-secondary">Apagar Música 🎵</button>
        <a href="logout.php" class="btn btn-danger ms-2">Cerrar Sesión</a>
      </div>
    </div>
  </header>

  <!-- 🔹 CONTENIDO PRINCIPAL -->
  <main id="contenido">
    <div class="container content">
      <?php if ($es_admin): ?>
        <div class="text-end mb-3">
          <a id="admin-btn" href="admin_productos.php" class="btn btn-warning">➕ Agregar Producto</a>
        </div>
      <?php endif; ?>
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        <?php foreach ($resultado as $row) { ?>
          <div class="col">
            <div class="card h-100">
              <?php 
                $id = $row['id'];
                $image = "images/productos/" . $id . "/principal.png";
                if (!file_exists($image)) { $image = "images/no-photo.png"; }
              ?>
              <img src="<?php echo $image; ?>" class="card-img-top img-fluid">
              <div class="card-body">
                <h5 class="card-title"><?php echo $row['nombre']; ?></h5>
                <p class="card-text">$<?php echo number_format($row['precio'], 2, '.', ','); ?></p>
                <button class="btn btn-primary detalles-btn" data-id="<?php echo $row['id']; ?>" data-token="<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>">Detalles</button>
                <button class="btn btn-success">Agregar</button>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </main>

  <!-- 🔹 AUDIO DE FONDO -->
  <audio id="backgroundMusic" autoplay loop>
    <source src="audio/tu_cancion.mp3" type="audio/mpeg">
  </audio>

  <!-- 🔹 SCRIPTS -->
  <script>
    setTimeout(() => {
      document.getElementById("contenido").classList.add("mostrar");
      document.getElementById("main-navbar").style.opacity = "1";
      document.getElementById("main-navbar").style.transform = "translateY(0)";
      <?php if ($es_admin): ?> 
        document.getElementById("admin-btn").style.opacity = "1";
        document.getElementById("admin-btn").style.transform = "translateY(0)";
      <?php endif; ?>
      document.body.style.overflow = "auto"; // 🔥 Habilita el scroll después de la animación
    }, 3000);
  </script>

</body>
</html>
