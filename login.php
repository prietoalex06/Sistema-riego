<?php
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'base';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        header("Location: pag1.html");
        exit;
    } else {
        $error = "Invalid username or password";
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
        <p>No tenes cuenta?? <a href="register.php">Register</a></p>
    </div>
</body>
</html>