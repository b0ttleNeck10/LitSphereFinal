<?php
    session_start();
    include('../connection.php');
    
    // Ensure the user is logged in (optional, if needed for authorization)
    if (!isset($_SESSION['fname'])) {
        echo "You must be logged in to edit books.";
        exit();
    }

    // Check if form data is set (through POST)
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bookID'])) {
        $bookID = $_POST['bookID'];  // Get the BookID from the form

        // Get other form fields
        $title = mysqli_real_escape_string($conn, $_POST['editBTitle']);
        $author = mysqli_real_escape_string($conn, $_POST['editBAuthor']);
        $genre = mysqli_real_escape_string($conn, $_POST['editGenre']);
        $description = mysqli_real_escape_string($conn, $_POST['editBDesc']);
        
        // Check if a new image has been uploaded
        if (isset($_FILES['editMyImg']) && $_FILES['editMyImg']['error'] === UPLOAD_ERR_OK) {
            $imageTmpName = $_FILES['editMyImg']['tmp_name'];
            $imageName = $_FILES['editMyImg']['name'];
            $imagePath = '../book_img' . $imageName;  // Example path to save the image
            
            // Move the uploaded file to the server folder (ensure 'uploads' folder exists and is writable)
            move_uploaded_file($imageTmpName, $imagePath);
        } else {
            // If no new image, retain the old image (fetch existing image URL)
            $sql = "SELECT CoverImageURL FROM Books WHERE BookID = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $bookID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            $imagePath = $row['CoverImageURL'];  // Keep existing image
        }

        // Update the database with the new book details
        $updateSql = "UPDATE Books SET Title = ?, AuthorName = ?, Genre = ?, Description = ?, CoverImageURL = ? WHERE BookID = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        
        if ($updateStmt === false) {
            echo json_encode(["error" => "Failed to prepare the update query"]);
            exit();
        }

        mysqli_stmt_bind_param($updateStmt, "sssssi", $title, $author, $genre, $description, $imagePath, $bookID);
        
        if (mysqli_stmt_execute($updateStmt)) {
            echo json_encode(["success" => "Book updated successfully"]);
        } else {
            echo json_encode(["error" => "Failed to update the book"]);
        }
        
        mysqli_stmt_close($updateStmt);
    } else {
        echo json_encode(["error" => "Invalid request."]);
    }

    mysqli_close($conn);
?>
