<?php
session_start();
include("header.php");
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    die("Access denied.");
}

if (!isset($_GET['id'])) {
    die("User ID not provided.");
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    die("User not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!empty($password)) {
        if ($password !== $confirm_password) {
            echo "<script>alert('Passwords do not match.');</script>";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET name = ?, username = ?, role = ?, password = ? WHERE id = ?");
            $update->bind_param("ssssi", $name, $username, $role, $hashedPassword, $id);
            if ($update->execute()) {
                header("Location: manage_user.php");
                exit;
            } else {
                echo "Update failed.";
            }
        }
    } else {
        $update = $conn->prepare("UPDATE users SET name = ?, username = ?, role = ? WHERE id = ?");
        $update->bind_param("sssi", $name, $username, $role, $id);
        if ($update->execute()) {
            header("Location: manage_user.php");
            exit;
        } else {
            echo "Update failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="../css/edit_user.css">
</head>
<body>
    <h2 style="text-align:center;">Edit User</h2>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

        <label>Username:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

        <label>Role:</label>
        <select name="role" required>
            <option value="Admin" <?= $user['role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
            <option value="Staff" <?= $user['role'] === 'Staff' ? 'selected' : '' ?>>Staff</option>
        </select>

        <label>New Password:</label>
        <input type="password" name="password" placeholder="Leave blank to keep current password">

        <label>Re-type Password:</label>
        <input type="password" name="confirm_password" placeholder="Re-type new password">

        <br>
        <input type="submit" value="Save Changes">
        <button type="button" onclick="window.location.href='manage_user.php'">Back</button>
    </form>
</body>
</html>
