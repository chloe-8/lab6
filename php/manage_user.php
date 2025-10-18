<?php

include("header.php");
include("database.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    die("Access denied.");
}

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

if ($searchQuery) {
    $userData = $conn->prepare("SELECT * FROM users WHERE name LIKE ? OR username LIKE ? OR role LIKE ?");
    $searchTerm = "%" . $searchQuery . "%";
    $userData->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
} else {
    $userData = $conn->prepare("SELECT * FROM users");
}

$userData->execute();
$result = $userData->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/manage_user.css">
    <title>User Management</title>
    <style>
        .hidden { display: none; }
    </style>
    <script src="../js/manage_user.js"></script>
</head>
<body>
<h2>Manage Users</h2><br>
<form method="GET" action="">
    <input type="text" id="searchInput" name="search" placeholder="Search users..." oninput="filterTable()">
    <button type="submit">Search</button>

    <button type="button" onclick="window.location.href='add_user.php'">Add User</button>
    
</form>

<table id="userTable" border="1">
    <thead>
        <tr>
            <th>NAME</th>
            <th>USERNAME</th>
            <th>ROLE</th>
            <th>CREATED AT</th>
            <th>ACTION</th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="userRow">
                    <td><?= htmlspecialchars($row["name"]) ?></td>
                    <td><?= htmlspecialchars($row["username"]) ?></td>
                    <td><?= htmlspecialchars($row["role"]) ?></td>
                    <td><?= htmlspecialchars($row["created_at"]) ?></td>
                    <td>
                        <button><a href="edit_user.php?id=<?= $row['id'] ?>">Edit</a></button> |
                        <button class="cancel-btn" onclick="openCancelModal(<?= $row['id'] ?>)">Delete</button>
                    </td>
                    <td>
                        <select onchange="updateStatus(<?= $row['id'] ?>, this.value)">
                            <option value="1" <?= $row['is_active'] ? 'selected' : '' ?>>Activate</option>
                            <option value="0" <?= !$row['is_active'] ? 'selected' : '' ?>>Deactivate</option>
                        </select>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No results found</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<div id="cancelConfirmModal" class="modal">
    <div class="modal-content">
        <h3>Delete User</h3>
        <p>Are you sure you want to delete this?</p>
        <button class="confirm-btn" onclick="confirmCancel()">Yes</button>
        <button class="cancel-modal-btn" onclick="closeModalById('cancelConfirmModal')">No</button>
    </div>  
    <input type="hidden" id="appointmentId">
</div>

<div id="statusModal" class="modal">
    <div class="modal-content1">
        <h3>Status Update</h3>
        <p id="statusMessage"></p>
        <button onclick="closeModalById('statusModal')">OK</button>
    </div>
</div>


</body>
</html>
