<?php
    // Start the session to ensure user is logged in
    session_start();

    // Include your database connection file
    include('../connection.php');

    // Ensure the user is logged in (based on session data)
    if (!isset($_SESSION['fname'])) {
        echo "You must be logged in to delete a book.";
        exit();
    }

    // Check if the bookID is passed via GET
    if (isset($_GET['bookID'])) {
        $bookID = $_GET['bookID'];

        // Prepare the SQL statement to delete the book from the database
        $sql = "DELETE FROM Books WHERE BookID = ?";
        $stmt = $conn->prepare($sql);

        // Bind the bookID parameter (assuming it's an integer)
        $stmt->bind_param("i", $bookID);

        // Execute the query
        if ($stmt->execute()) {
            echo "Book deleted successfully.";
        } else {
            echo "Error deleting book: " . $conn->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Book ID is missing.";
    }

    // Close the database connection
    $conn->close();
?>
