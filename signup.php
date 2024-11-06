<?php
    include('connection.php');

    // Check if form data was posted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get user input
        $firstName = trim($_POST['firstname']);
        $lastName = trim($_POST['lastname']);
        $password = trim($_POST['password']);
        $email = trim($_POST['email']);
        $confirmPassword = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

        // Basic validation
        if (empty($firstName) || empty($lastName) || empty($password) || empty($email)) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
            exit();
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
            exit();
        }

        // Password match check
        if ($password !== $confirmPassword) {
            echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
            exit();
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if email exists
        $checkEmailStmt = $conn->prepare("SELECT * FROM Users WHERE Email = ?");
        $checkEmailStmt->bind_param("s", $email);
        $checkEmailStmt->execute();
        $result = $checkEmailStmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'This email is already in use.']);
        } else {
            // Insert new user into database
            $insertStmt = $conn->prepare("INSERT INTO Users (FirstName, LastName, PasswordHash, Email) VALUES (?, ?, ?, ?)");
            $insertStmt->bind_param("ssss", $firstName, $lastName, $hashedPassword, $email);

            if ($insertStmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Sign up successful!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error: Could not execute the query.']);
            }

            $insertStmt->close();
        }

        $checkEmailStmt->close();
        $conn->close();
    }
?>