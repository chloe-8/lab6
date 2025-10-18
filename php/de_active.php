<?php
session_start();
include("database.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user ID and new status
    $userId = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $newStatus = isset($_POST['new_status']) ? $_POST['new_status'] : '';

    if ($userId && ($newStatus == 'Activate' || $newStatus == 'Deactivate')) {
        // Determine the status value (1 for active, 0 for inactive)
        $isActive = ($newStatus == 'Activate') ? 1 : 0;

        // Update the user's status in the database
        $stmt = $conn->prepare("UPDATE users SET is_active = ? WHERE id = ?");
        $stmt->bind_param("ii", $isActive, $userId);

        if ($stmt->execute()) {
            echo 'success'; // Success message
        } else {
            echo 'error'; // Error message
        }

        $stmt->close();
    } else {
        echo 'error'; // Invalid request
    }
}
?>
