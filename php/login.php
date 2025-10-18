<?php
session_start();
include("database.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    $sql = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $sql->bind_param("s", $username);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $rows = $result->fetch_assoc();

        if (!isset($_SESSION['logged_user']) || $_SESSION['logged_user'] == $rows['username']) {
            $_SESSION['logged_user'] = $rows['username'];
            $_SESSION['role'] = $rows['role'];
            $_SESSION['user_id'] = $rows['id'];
            header("location: index.php");
            exit();
        }
    } else {
        echo "<script>alert('Wrong Credentials');</script>";
    }

    $sql->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <form action="login.php" method="POST">
        <div class="login-container">
            <!-- Left: Login Form -->
            <div class="login">
                <h1>Login</h1>

                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>

                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>

                <button type="submit">Login</button>
            </div>

            <!-- Right: Logo -->
            <div class="logo">
                <img src="../uploads/LOGO.png" alt="Logo">
            </div>
        </div>
    </form>
</body>
</html>
