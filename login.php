<?php
    session_start();
    include 'connection.php';

    // Initialize response variable to avoid undefined variable warning
    $response = array('status' => 'error', 'message' => 'An error occurred.');

    if (isset($_POST['logIn'])) {
        // Get the user inputs
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Debugging: Log email and password
        error_log('Email: ' . $email);

        // Use prepared statements to prevent SQL injection
        if ($stmt = $conn->prepare("SELECT * FROM Users WHERE Email = ?")) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the user exists
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Verify the password
                if (password_verify($password, $row['PasswordHash'])) {
                    // Regenerate session ID for security
                    session_regenerate_id(true);
                    $_SESSION['userID'] = $row['UserID']; // Store user ID in session
                    $_SESSION['username'] = $row['Email']; // Store user email in session
                    $_SESSION['fname'] = $row['FirstName']; // Store user first name in session
                    $_SESSION['fullName'] = $row['FirstName'] . ' ' . $row['LastName']; // Optional: store full name

                    // Debugging: Check user role
                    error_log('User is admin: ' . ($email === 'admin@example.com' ? 'Yes' : 'No'));

                    // Redirect URL based on the user type (admin or regular user)
                    if ($email === 'admin@example.com') {
                        $response = array('status' => 'success', 'redirectUrl' => 'dashboard/notification.php');
                    } else {
                        $response = array('status' => 'success', 'redirectUrl' => 'dashboard/bookprev.php');
                    }
                } else {
                    $response = array('status' => 'error', 'message' => '* Invalid email or password.');
                }
            } else {
                $response = array('status' => 'error', 'message' => '* Invalid email or password.');
            }

            // Close the statement
            $stmt->close();
        } else {
            $response = array('status' => 'error', 'message' => 'Database query failed: ' . $conn->error);
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Login request not received.');
    }

    // Return JSON response
    echo json_encode($response);
?>