<?php
// Iniciar una sesión
session_start();

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "usuarios";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Procesar los datos cuando se envíe el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $confirm_email = $_POST["confirm_email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $cedula = $_POST["cedula"];

    // Verificar si los correos coinciden
    if ($email !== $confirm_email) {
        echo "Los correos electrónicos no coinciden.";
        exit;
    }

    // Verificar si las contraseñas coinciden
    if ($password !== $confirm_password) {
        echo "Las contraseñas no coinciden.";
        exit;
    }

    // Hash de la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Preparar la consulta para insertar los datos en la base de datos
    $sql = "INSERT INTO usuarios (email, password, cedula) VALUES (?, ?, ?)";

    // Preparar la consulta para evitar inyecciones SQL
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $email, $hashed_password, $cedula);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Registro exitoso.";
    } else {
        echo "Error en el registro: " . $conn->error;
    }

    // Cerrar la consulta y la conexión
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Completado</title>
</head>
<body>
    <p>Tu registro ha sido exitoso. <a href="index.html">Ir a la Página Principal</a></p>
</body>
</html>
