<?php
session_start();
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$carrito = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
?>

<?php if (empty($carrito)): ?>
  <p>No hay productos en el carrito.</p>
<?php else: ?>
  <table class="table">
    <thead>
      <tr>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio</th>
        <th>Subtotal</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($carrito as $id => $item): 
          $sql = $con->prepare("SELECT nombre, precio FROM productos WHERE id = ?");
          $sql->execute([$id]);
          $producto = $sql->fetch(PDO::FETCH_ASSOC);

          if ($producto): // Verificamos que el producto exista
            $subtotal = $producto['precio'] * $item['quantity'];
            $total += $subtotal;
      ?>
        <tr>
          <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
          <td><?php echo $item['quantity']; ?></td>
          <td><?php echo number_format($producto['precio'], 2); ?></td>
          <td><?php echo number_format($subtotal, 2); ?></td>
          <td>
          <button class="btn btn-danger btn-sm eliminar-producto" data-id="<?php echo $id; ?>">Eliminar</button>
          </td>
        </tr>
      <?php endif; endforeach; ?>
    </tbody>
  </table>
  <h3>Total: <?php echo number_format($total, 2); ?></h3>

<?php endif; ?>
