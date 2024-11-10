<?php
    include('../connection.php');
    
    // Check if bookID is passed
    if (isset($_GET['bookID'])) {
        $bookID = $_GET['bookID'];

        // Prepare SQL statement
        $sql = "SELECT * FROM Books WHERE BookID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt === false) {
            echo json_encode(["error" => "Failed to prepare the query"]);
            error_log("SQL prepare failed: " . mysqli_error($conn));  // Log error
            exit();
        }
        
        mysqli_stmt_bind_param($stmt, "i", $bookID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        // Check if the book exists
        if ($row = mysqli_fetch_assoc($result)) {
            echo json_encode($row);  // Return book details as JSON
        } else {
            echo json_encode(["error" => "Book not found"]);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(["error" => "Book ID not provided"]);
    }

    mysqli_close($conn);
?>
