<?php
    session_start();
    include('../connection.php');

    // Check if form is submitted via AJAX (with POST request)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve and sanitize form data
        $title = filter_var($_POST['bTitle'], FILTER_SANITIZE_STRING);
        $author = filter_var($_POST['bAuthor'], FILTER_SANITIZE_STRING);
        $genre = filter_var($_POST['genre'], FILTER_SANITIZE_STRING);
        $description = filter_var($_POST['bDesc'], FILTER_SANITIZE_STRING);

        // Handle the image upload
        if (isset($_FILES['myImg']) && $_FILES['myImg']['error'] == 0) {
            $fileTmpPath = $_FILES['myImg']['tmp_name'];
            $fileName = basename($_FILES['myImg']['name']);
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            // Validate file extension
            if (in_array($fileExtension, $allowedExtensions)) {
                $uploadDir = '../book_img/';
                $newFileName = uniqid('book_', true) . '.' . $fileExtension; // Unique file name
                $uploadFilePath = $uploadDir . $newFileName;

                // Move the uploaded file to the target directory
                if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                    $coverImageURL = $uploadFilePath;

                    // Use prepared statements to prevent SQL injection
                    $sql = "INSERT INTO Books (Title, AuthorName, Genre, CoverImageURL, Description, Status)
                            VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $status = 'Available';
                    $stmt->bind_param("ssssss", $title, $author, $genre, $coverImageURL, $description, $status);

                    if ($stmt->execute()) {
                        echo json_encode(['status' => 'success', 'message' => 'New book added successfully']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $stmt->error]);
                    }

                    $stmt->close();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error moving uploaded file.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Allowed types: jpg, jpeg, png, gif.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No image uploaded or file upload error.']);
        }
    }

    // Close the connection
    $conn->close();
?>
