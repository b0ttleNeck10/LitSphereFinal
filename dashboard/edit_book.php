<?php
    session_start();
    include('../connection.php');

    // Ensure user is logged in
    if (!isset($_SESSION['fname'])) {
        echo json_encode(['status' => 'error', 'message' => 'You must be logged in to edit books.']);
        exit();
    }

    // Get the form data
    $bookID = $_POST['bookID'];
    $title = $_POST['editBTitle'];
    $author = $_POST['editBAuthor'];
    $genre = $_POST['editGenre'];
    $description = $_POST['editBDesc'];

    // Check if bookID exists
    if (empty($bookID)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid book ID.']);
        exit();
    }

    // Get the current cover image URL from the database if it exists
    $sql = "SELECT CoverImageURL FROM Books WHERE BookID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $bookID);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($currentImageURL);
    $stmt->fetch();
    $stmt->close();

    // Handle image upload (if any)
    if (isset($_FILES['editMyImg']) && $_FILES['editMyImg']['error'] == 0) {
        $uploadDir = '../book_img/';
        $imageName = basename($_FILES['editMyImg']['name']);
        $uploadFile = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['editMyImg']['tmp_name'], $uploadFile)) {
            // New image uploaded, use the new file
            $imageURL = $uploadFile;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error uploading image.']);
            exit();
        }
    } else {
        // No image uploaded, use the current image URL
        $imageURL = $currentImageURL;
    }

    // Update the book in the database
    $sql = "UPDATE Books SET Title=?, AuthorName=?, Genre=?, Description=?, CoverImageURL=? WHERE BookID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssi', $title, $author, $genre, $description, $imageURL, $bookID);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Book updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update book.']);
    }

    $stmt->close();
    $conn->close();
?>
