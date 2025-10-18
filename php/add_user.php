<?php

include("header.php");
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    die("Access denied.");
}


if($_SERVER['REQUEST_METHOD'] == "POST"){

    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $rePassword = filter_input(INPUT_POST, "repassword", FILTER_SANITIZE_SPECIAL_CHARS);
    $role = filter_input(INPUT_POST, "role", FILTER_SANITIZE_SPECIAL_CHARS);
    $hashPassword = password_hash($password, PASSWORD_DEFAULT);
    $created_at = date("Y-m-d H:i:s");


    $checkDup = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $checkDup->bind_param("s", $username);
    $checkDup->execute();
    $result = $checkDup->get_result();

    if($result->num_rows > 0 ){

        echo"Duplicate Username";

    }else if($password != $rePassword){
        echo"Error Password";
    }else{

        $sql = $conn->prepare("INSERT INTO users(name,username,password,role,created_at) VALUES (?, ?, ?, ?, ?)");
        $sql->bind_param("sssss",$name,$username,$hashPassword,$role,$created_at);

        if($sql->execute()){
            header("Location: add_user.php?success=1");
            exit;
        }           
    }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/add_users.css">
    <title>Document</title>
</head>
<body>
    
<form action="add_user.php" method="POST" onsubmit="return validateForm()">
    <div class="add_user-container">
        <h2>Add Users</h2>

        <label for="name">Name</label>
        <input type="text" name="name" id="name">
        <div id="nameError" class="error"></div>

        <label for="username">Username</label>
        <input type="text" name="username" id="username">
        <div id="usernameError" class="error"></div>

        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        <div id="passwordError" class="error"></div>

        <label for="repassword">Re-Type Password</label>
        <input type="password" name="repassword" id="repassword">
        <div id="repasswordError" class="error"></div>

        <label for="role">Select Role:</label>
        <select name="role" id="role">
            <option value="">--Select Role--</option>
            <option value="Admin">Admin</option>
            <option value="Special User">Special User</option>
            <option value="User">User</option>
        </select>
        <div id="roleError" class="error"></div>

        <br>
        <button type="submit">Add User</button>
        <button type="button" onclick="window.location.href='manage_user.php'">Back</button>
    </div>
</form>

<!-- Success Modal -->
<div id="successModal" class="modal" style="display:none;">
    <div class="modal-content">
        <p>User registered successfully!</p>
        <button onclick="closeModal()">OK</button>
    </div>
</div>

<script>
function closeModal() {
    document.getElementById("successModal").style.display = "none";
    // Remove the query param from the URL without reloading
    window.history.replaceState({}, document.title, "add_user.php");
}

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get("success") === "1") {
        document.getElementById("successModal").style.display = "flex";
    }
}
function validateForm() {
    let isValid = true;

    // Clear all previous errors
    document.querySelectorAll('.error').forEach(e => e.textContent = '');

    const name = document.getElementById('name').value.trim();
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
    const repassword = document.getElementById('repassword').value.trim();
    const role = document.getElementById('role').value;

    if (name === "") {
        document.getElementById('nameError').textContent = "Name is required.";
        isValid = false;
    }
    if (username === "") {
        document.getElementById('usernameError').textContent = "Username is required.";
        isValid = false;
    }
    if (password === "") {
        document.getElementById('passwordError').textContent = "Password is required.";
        isValid = false;
    }
    if (repassword === "") {
        document.getElementById('repasswordError').textContent = "Please re-type the password.";
        isValid = false;
    } else if (password !== repassword) {
        document.getElementById('repasswordError').textContent = "Passwords do not match.";
        isValid = false;
    }
    if (role === "") {
        document.getElementById('roleError').textContent = "Please select a role.";
        isValid = false;
    }

    return isValid;
}
</script>

</body>
</html>