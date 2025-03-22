<?php
require 'config/database.php';

$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$rol = $_POST['rol'];

$db = new Database();
$con = $db->conectar();

// Verificar si el correo ya existe
$sql = $con->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
$sql->execute([$correo]);


if ($sql->fetchColumn() > 0) {
    echo "El correo ya está registrado. Intenta con otro.";
    exit;
}

$sql = $con->prepare("INSERT INTO usuarios (nombre, email, pass_hash, rol) VALUES (?, ?, ?, ?)");
$sql->execute([$nombre, $correo, $password, $rol]);



echo "Usuario registrado exitosamente. Ahora puedes iniciar sesión.";
header("Location: login.php");
?>
