<?php
    // Include your database connection
    include('../connection.php');

    // Get the data from the POST request
    if (isset($_POST['userID']) && isset($_POST['reason']) && isset($_POST['days'])) {
        $userID = $_POST['userID'];
        $reason = $_POST['reason'];
        $days = $_POST['days'];
        
        // Calculate the suspension end date
        $suspensionDate = date('Y-m-d H:i:s'); // Current timestamp
        $suspensionEndDate = date('Y-m-d H:i:s', strtotime("+$days days")); // Add suspension days
        
        // Prepare the SQL query to update the user's suspension status
        $sql = "UPDATE Users SET 
                IsSuspended = 1, 
                SuspensionDate = ?, 
                SuspensionDuration = ?, 
                SuspensionReason = ? 
                WHERE UserID = ?";

        // Prepare and bind the query
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisi", $suspensionDate, $days, $reason, $userID);
        
        // Execute the query
        if ($stmt->execute()) {
            // Success: Return the success status with suspension duration
            echo json_encode([
                'success' => true,
                'suspensionDuration' => $days
            ]);
        } else {
            // Failure: Return an error message
            echo json_encode(['success' => false]);
        }
        
        // Close the statement
        $stmt->close();
    } else {
        // Invalid request
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    }

    // Close the database connection
    mysqli_close($conn);
?>
