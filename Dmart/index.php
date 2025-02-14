<?php 
require 'config/database.php';
require 'config/config.php';

$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
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
    /* 🎨 FONDO ANIMADO */
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Lobster', cursive;
      background: linear-gradient(-45deg, #ffdde1, #ee9ca7, #ffdde1, #ffebf7);
      background-size: 400% 400%;
      animation: gradientBG 10s ease infinite;
    }

    @keyframes gradientBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* 🏪 HEADER */
    .bg-sky-blue {
      background-color: #87CEEB;
    }

    .custom-font {
      color: white;
      font-size: 24px;
    }

    .swing-image {
      animation: swing 1s ease-in-out infinite;
      height: 75px;
      transform-origin: bottom;
    }

    @keyframes swing {
      0% { transform: rotateX(0); }
      50% { transform: rotateX(40deg); }
      100% { transform: rotateX(0); }
    }

    /* 🛍️ ESTILOS DE LAS TARJETAS */
    .content {
      margin-top: 50px;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      background: #ffffff;
      overflow: hidden;
      position: relative;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .card img {
      border-top-left-radius: 15px;
      border-top-right-radius: 15px;
    }

    .card-body {
      text-align: center;
      padding: 20px;
    }

    /* 🎮 ANIMACIÓN SUAVE EN LOS BOTONES */
    @keyframes button-move {
      0% { transform: translateX(0); }
      50% { transform: translateX(5px); }
      100% { transform: translateX(0); }
    }

    .btn-hover:hover {
      animation: button-move 0.4s ease-in-out infinite;
    }
    
    /* 🎵 BOTÓN DE MÚSICA */
    #toggleMusicButton {
      margin-left: 15px;
    }

    /* 🎨 MODAL PARA DETALLES CON COLOR ALEATORIO */
    .modal-lg {
      max-width: 80%;
    }

    #modalDetalles {
      transition: background-color 0.5s ease-in-out;
    }

    #modalDetalles iframe {
      width: 100%;
      height: 500px;
      border: none;
    }
  </style>
</head>
<body>
  <!-- 🏠 HEADER -->
  <header class="navbar navbar-expand-lg navbar-dark bg-sky-blue shadow-sm">
    <div class="container">
      <a href="#" class="navbar-brand">
        <img src="cinnamoroll.png" alt="Cinnamoroll" class="swing-image">
        <strong class="custom-font">Cinnamoroll Sweet Shop</strong>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarHeader">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a href="#" class="nav-link active">Catálogo</a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link active">Contacto</a>
          </li>
        </ul>
        <button id="toggleMusicButton" class="btn btn-secondary">Apagar Música</button>
        <a href="logout.php" class="btn btn-danger ms-2">Cerrar Sesión</a>
      </div>
    </div>
  </header>

  <!-- 🎵 AUDIO DE FONDO -->
  <audio id="backgroundMusic" autoplay loop>
    <source src="audio/Shop Channel.mp3" type="audio/mpeg">
    Tu navegador no soporta la reproducción de audio.
  </audio>

  <!-- 🛒 CONTENIDO PRINCIPAL -->
  <main>
    <div class="container content">
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
                <div class="d-flex justify-content-between align-items-center">
                  <button class="btn btn-primary btn-sm btn-hover detalles-btn" 
                          data-id="<?php echo $row['id']; ?>" 
                          data-token="<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>">Detalles</button>
                  <button class="btn btn-success btn-sm btn-hover agregar-btn">Agregar</button>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </main>

  <!-- 🛍️ MODAL PARA DETALLES -->
  <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDetallesLabel">Detalles del Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <iframe id="detallesFrame"></iframe>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // 🎵 CONTROL DE MÚSICA
    document.getElementById("toggleMusicButton").addEventListener("click", () => {
      let music = document.getElementById("backgroundMusic");
      music.paused ? music.play() : music.pause();
    });

    // 🎨 MODAL DE DETALLES CON COLOR ALEATORIO
    document.querySelectorAll(".detalles-btn").forEach(button => {
      button.addEventListener("click", function() {
        const id = this.dataset.id;
        const token = this.dataset.token;
        document.getElementById("detallesFrame").src = `detalles.php?id=${id}&token=${token}`;
        document.getElementById("modalDetalles").style.backgroundColor = `hsl(${Math.random() * 360}, 100%, 90%)`;
        new bootstrap.Modal(document.getElementById("modalDetalles")).show();
      });
    });
  </script>

</body>
</html>
