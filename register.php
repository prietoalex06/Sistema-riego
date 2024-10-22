
<?php
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'base';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}


function usuarioExiste($conn, $username) {
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result 
= $stmt->get_result();
    return $result->num_rows  
> 0;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {
        $error 
= "Las contraseñas no coinciden";
    } else 
{
        if (usuarioExiste($conn, $username)) {
            $error = "El nombre de usuario ya está en uso";
        } else {
            $hashed_password = hashPassword($password);

            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $hashed_password);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header("Location: index.php");
                exit;
            } else {
                $error = "Error al registrar el usuario";
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <?php if (isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" class="form-control"><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control"><br><br>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control"><br><br>
            <input type="submit" value="Register" class="btn">
        </form>
    </div>
</body>
</html>