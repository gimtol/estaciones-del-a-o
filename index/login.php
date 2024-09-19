<?php
// Iniciar la sesión
session_start();

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "usuarios";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Procesar los datos cuando se envíe el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Preparar la consulta para evitar inyecciones SQL
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);

    // Ejecutar la consulta
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el usuario
    if ($result->num_rows > 0) {
        // Obtener los datos del usuario
        $row = $result->fetch_assoc();
        
        // Verificar la contraseña
        if (password_verify($password, $row['password'])) {
            // Inicio de sesión exitoso, guardar en la sesión
            $_SESSION['email'] = $email;
            echo "Inicio de sesión exitoso.";
            header("Location: index.html"); // Redirigir a la página principal
            exit;
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
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
    <title>Inicio de Sesión Fallido</title>
</head>
<body>
    <p>Hubo un problema con tu inicio de sesión. <a href="login.html">Intentar nuevamente</a></p>
</body>
</html>
