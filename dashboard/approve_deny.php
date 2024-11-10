<?php
    session_start();
    if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin@example.com') {
        header("Location: ../index.php"); // Redirect if not admin
        exit();
    }

    include('../connection.php');

    // Check if the borrow ID is provided and action is set
    if (isset($_POST['borrow_id']) && isset($_POST['action'])) {
        $borrowID = $_POST['borrow_id'];
        $action = $_POST['action'];

        if ($action == 'approve') {
            // Update the status to 'Active' for the borrow request
            $query = "UPDATE Borrow SET Status = 'Active' WHERE BorrowID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $borrowID);
            $stmt->execute();

            // Optionally, update the book status to 'Borrowed' when it's approved
            $updateBookQuery = "UPDATE Books SET Status = 'Borrowed' WHERE BookID = (SELECT BookID FROM Borrow WHERE BorrowID = ?)";
            $updateBookStmt = $conn->prepare($updateBookQuery);
            $updateBookStmt->bind_param("i", $borrowID);
            $updateBookStmt->execute();
        } else {
            // Deny the request and update status to 'Denied'
            $query = "UPDATE Borrow SET Status = 'Denied' WHERE BorrowID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $borrowID);
            $stmt->execute();   

            // Set the book status to 'Available' when the request is denied
            $updateBookQuery = "UPDATE Books SET Status = 'Available' WHERE BookID = (SELECT BookID FROM Borrow WHERE BorrowID = ?)";
            $updateBookStmt = $conn->prepare($updateBookQuery);
            $updateBookStmt->bind_param("i", $borrowID);
            $updateBookStmt->execute();
        }

        // Redirect back to the notifications page
        header("Location: notification.php");
        exit();
    } else {
        echo "Invalid request.";
    }
?>
