<?php
    session_start();
    include 'connection.php';

    // Initialize response variable to avoid undefined variable warning
    $response = array('status' => 'error', 'message' => 'An error occurred.');

    if (isset($_POST['logIn'])) {
        // 1. Get the user inputs
        $email = $_POST['email'];
        $password = $_POST['password'];

        // 2. Use prepared statements to prevent SQL injection
        if ($stmt = $conn->prepare("SELECT * FROM Users WHERE Email = ?")) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            // 3. Check if the user exists
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // 4. Verify the password
                if (password_verify($password, $row['PasswordHash'])) {
                    // 5. Regenerate session ID for security
                    session_regenerate_id(true);
                    $_SESSION['userID'] = $row['UserID']; // Store user ID in session
                    $_SESSION['username'] = $row['Email']; // Store user email in session
                    $_SESSION['fname'] = $row['FirstName']; // Store user first name in session
                    $_SESSION['fullName'] = $row['FirstName'] . ' ' . $row['LastName']; // Optional: store full name

                    // Redirect URL based on the user type (admin or regular user)
                    if ($email === 'admin@example.com') {
                        $response = array('status' => 'success', 'redirectUrl' => 'dashboard/notification.php');
                    } else {
                        $response = array('status' => 'success', 'redirectUrl' => 'dashboard/bookprev.php');
                    }
                } else {
                    $response = array('status' => 'error', 'message' => '* You have entered an invalid email or password. Please try again');
                }
            } else {
                $response = array('status' => 'error', 'message' => '* You have entered an invalid email or password. Please try again');
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
