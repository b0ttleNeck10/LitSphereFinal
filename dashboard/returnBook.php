<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    session_start();
    include('../connection.php');

    // Check if the user is logged in
    if (!isset($_SESSION['username'])) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
        exit();
    }

    $username = $_SESSION['username'];

    // Fetch the UserID from the database
    $sql_user = "SELECT UserID FROM Users WHERE Email = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("s", $username);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $row_user = $result_user->fetch_assoc();
    $userID = $row_user['UserID'] ?? null;

    if ($userID === null) {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
        exit();
    }

    // Ensure that book_ids is passed correctly
    if (!isset($_POST['book_ids'])) {
        echo json_encode(['status' => 'error', 'message' => 'No books selected']);
        exit();
    }

    $bookIds = json_decode($_POST['book_ids']);
    if (empty($bookIds)) {
        echo json_encode(['status' => 'error', 'message' => 'No valid book IDs received']);
        exit();
    }

    // Prepare the SQL to update the borrow table
    $placeholders = implode(',', array_fill(0, count($bookIds), '?'));  // Create placeholders for the query
    $sql = "UPDATE Borrow SET Status = 'Returned' WHERE BookID IN ($placeholders) AND UserID = ?";

    // Prepare the statement
    $types = str_repeat('i', count($bookIds)) . 'i';  // Prepare type string for parameter binding
    $bookIds[] = $userID;  // Add userID to the end of the array
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$bookIds);  // Unpack the array and pass all arguments at once

    // Execute the Borrow update
    if (!$stmt->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update Borrow table']);
        exit();
    }

    // Update the Books table to set the status to 'Available'
    $placeholdersBooks = implode(',', array_fill(0, count($bookIds) - 1, '?'));  // Exclude the last item (userID)
    $sql_books = "UPDATE Books SET Status = 'Available' WHERE BookID IN ($placeholdersBooks)";

    $stmt_books = $conn->prepare($sql_books);
    $types_books = str_repeat('i', count($bookIds) - 1);  // Prepare type string for binding book IDs
    array_pop($bookIds);  // Remove userID from the bookIds array for the Books table query
    $stmt_books->bind_param($types_books, ...$bookIds);

    if ($stmt_books->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Books returned and status updated successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update Books table']);
    }
?>
