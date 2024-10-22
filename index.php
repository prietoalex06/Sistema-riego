<?php

$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'base';


$conn = new mysqli($db_host, $db_username, $db_password, $db_name);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];


    $sql = "SELECT password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $stored_password 
= $row['password'];

        if (password_verify($password, $stored_password)) {
            session_start();
            $_SESSION['username'] = $username;
            header("Location: pag1.html");
            exit;
        } else {
            $error = "Usuario o contraseña inválidos";
        }
    } else {
        $error = "Usuario o contraseña inválidos";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" class="form-control"><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control"><br>
            <input type="submit" value="Login" class="btn">
        </form>
        <p>No tienes cuenta? <a href="register.php">Register</a></p>
    </div>
</body>
</html>