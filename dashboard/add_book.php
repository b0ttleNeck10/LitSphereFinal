<?php
    session_start();
    include('../connection.php');

    // Check if form is submitted via AJAX (with POST request)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve form data
        $title = $_POST['bTitle'];
        $author = $_POST['bAuthor'];
        $genre = $_POST['genre'];
        $description = $_POST['bDesc'];
`
        // Handle the image upload
        if (isset($_FILES['myImg']) && $_FILES['myImg']['error'] == 0) {
            $fileTmpPath = $_FILES['myImg']['tmp_name'];
            $fileName = $_FILES['myImg']['name'];
            $fileSize = $_FILES['myImg']['size'];
            $fileType = $_FILES['myImg']['type'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

            // Define the target directory for images   
            $uploadDir = '../book_img/';
            $uploadFilePath = $uploadDir . basename($fileName);

            // Move the uploaded file to the target directory
            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                // Image uploaded successfully, now insert the data into the database
                $coverImageURL = "../book_img/" . basename($fileName);

                // SQL query to insert book data
                $sql = "INSERT INTO Books (Title, AuthorName, Genre, CoverImageURL, Description, Status) 
                        VALUES ('$title', '$author', '$genre', '$coverImageURL', '$description', 'Available')";

                if ($conn->query($sql) === TRUE) {
                    // Return a success message with the book details
                    echo json_encode(['status' => 'success', 'message' => 'New book added successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $conn->error]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error uploading file.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No image uploaded or file upload error.']);
        }
    }

    // Close the connection
    $conn->close();
?>
