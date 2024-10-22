<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "base";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_SESSION['username'])) {
    $nombreUsuario = $_SESSION['username'];
} else {
    die("No hay sesión iniciada");
}

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error al preparar la consulta: " . $conn->error);
}

$stmt->bind_param("s", $nombreUsuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $email = htmlspecialchars($user['email']); // Agregado para mostrar el correo
    $nombreUsuario = htmlspecialchars($user['username']); 
} else {
    echo "Usuario no encontrado.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['logout'])) {
        // Cerrar sesión
        session_unset(); // Destruir todas las variables de sesión
        session_destroy(); // Destruir la sesión
        header("Location: login.php"); // Redirigir a la página de inicio de sesión
        exit();
    }

    if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword === $confirmPassword) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $stmtPass = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
            if ($stmtPass === false) {
                die("Error al preparar la consulta para actualizar la contraseña: " . $conn->error);
            }

            $stmtPass->bind_param("ss", $hashedPassword, $nombreUsuario);

            if ($stmtPass->execute()) {
                echo "Contraseña actualizada correctamente.";
            } else {
                echo "Error al actualizar la contraseña: " . $stmtPass->error;
            }
            $stmtPass->close();
        } else {
            echo "Las contraseñas no coinciden. Inténtalo de nuevo.";
        }
    }

    if (isset($_POST['email'])) {
        $newEmail = $_POST['email'];
        $stmtEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
        if ($stmtEmail === false) {
            die("Error al preparar la consulta para verificar el correo electrónico: " . $conn->error);
        }

        $stmtEmail->bind_param("s", $newEmail);
        $stmtEmail->execute();
        $resultEmail = $stmtEmail->get_result();

        if ($resultEmail->num_rows > 0) {
            echo "El correo electrónico ya existe. Inténtalo de nuevo.";
        } else {
            $stmtEmailUpdate = $conn->prepare("UPDATE users SET email = ? WHERE username = ?");
            if ($stmtEmailUpdate === false) {
                die("Error al preparar la consulta para actualizar el correo electrónico: " . $conn->error);
            }

            $stmtEmailUpdate->bind_param("ss", $newEmail, $nombreUsuario);

            if ($stmtEmailUpdate->execute()) {
                echo "Correo electrónico actualizado correctamente.";
            } else {
                echo "Error al actualizar el correo electrónico: " . $stmtEmailUpdate->error;
            }
            $stmtEmailUpdate->close();
        }
        $stmtEmail->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Mi Perfil</title>
</head>
<body>
    <header class="site-header">
        <nav class="nav-bar">
            <ul class="nav-links">
                <li><a href="pag1.html">Inicio</a></li>
                <li><a href="nosotros.html">Sobre Nosotros</a></li>
            </ul>
            <div class="perfil">
                <a>Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?>!</a>
            </div>
        </nav>
    </header>

    <main class="site-main">
        <h1>Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?>!</h1>
        <p>Tu correo electrónico: <?php echo htmlspecialchars($email); ?></p>

        <h2>Cambiar contraseña</h2>
        <form method="post">
            <label for="new_password">Nueva contraseña:</label>
            <input type="password" id="new_password" name="new_password" class="form-control" required>

            <label for="confirm_password">Confirmar contraseña:</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>

            <button type="submit" class="btn">Cambiar</button>
        </form>

        <h2>Actualizar correo electrónico</h2>
        <form method="post">
            <label for="email">Nuevo correo electrónico:</label>
            <input type="email" id="email" name="email" class="form-control" required>

            <button type="submit" class="btn">Actualizar</button>
        </form>

        <h2>Cerrar sesión</h2>
        <form method="post">
            <button type="submit" name="logout" class="btn">Cerrar sesión</button>
        </form>
    </main>

</body>
</html>
