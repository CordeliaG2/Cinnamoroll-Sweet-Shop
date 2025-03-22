<?php
require 'config/database.php';
session_start();

$correo = $_POST['correo'];
$password = $_POST['password'];

$db = new Database();
$con = $db->conectar();

// Verificar si el usuario existe
$sql = $con->prepare("SELECT id_usuario, pass_hash, rol FROM usuarios WHERE email = ?");
$sql->execute([$correo]);
$usuario = $sql->fetch(PDO::FETCH_ASSOC);

if ($usuario && password_verify($password, $usuario['pass_hash'])) {
    // Configurar la sesión solo una vez
    if (!isset($_SESSION['id_usuario'])) {
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['rol'] = $usuario['rol'];
    }

    // Redirigir a la página principal después del inicio de sesión
    header("Location: index.php");
    exit;
} else {
    echo "Credenciales incorrectas.";
}
?>

