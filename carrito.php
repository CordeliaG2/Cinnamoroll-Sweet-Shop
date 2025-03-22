<?php
session_start();
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tu Carrito</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-4">
    <h1>Tu Carrito</h1>
    <?php if (empty($cart)): ?>
      <p>No hay productos en el carrito.</p>
    <?php else: ?>
      <table class="table">
        <thead>
          <tr>
            <th>ID del Producto</th>
            <th>Cantidad</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cart as $product_id => $item): ?>
            <tr>
              <td><?php echo htmlspecialchars($product_id); ?></td>
              <td><?php echo htmlspecialchars($item['quantity']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
    <a href="index.php" class="btn btn-primary">Seguir Comprando</a>
  </div>
</body>
</html>
