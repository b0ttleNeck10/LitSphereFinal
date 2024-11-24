<?php
    session_start();
    include('../connection.php');

    if (!isset($_SESSION['username'])) {
        header("Location: ../index.php"); // Redirect to login if not logged in
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_POST['userID'];

        // Update the status to 'Cleared' for the current user's borrowing history
        $updateQuery = $conn->prepare("
            UPDATE BorrowingHistory
            SET Status = 'Cleared'
            WHERE UserID = ? AND Status = 'Active';
        ");

        $updateQuery->bind_param("i", $userID);
        if ($updateQuery->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false]);
        }
    }
?>
