<?php
    session_start();
    include('../connection.php');

    // Check if user is logged in
    if (!isset($_SESSION['fname'])) {
        header("Location: ../index.php"); // Redirect to login if not logged in
        exit();
    }

    $username = $_SESSION['username']; // Assuming username is stored in session

    // Initialize error and success messages
    $errorMessages = [];
    $successMessage = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the form data
        $currentPassword = isset($_POST['current_password']) ? $_POST['current_password'] : '';
        $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
        $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

        // Check if current password, new password, and confirmation are provided
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $errorMessages[] = "All fields are required.";
        }

        // Check if the new password and confirmation match
        if ($newPassword !== $confirmPassword) {
            $errorMessages[] = "New password and confirmation do not match.";
        }

        // Check if the new password meets the minimum length requirement
        if (strlen($newPassword) < 6) {
            $errorMessages[] = "Password must be at least 6 characters long.";
        }

        // Password strength validation: At least one letter, one number, and one special character
        if (!preg_match('/[A-Za-z]/', $newPassword) || !preg_match('/\d/', $newPassword) || !preg_match('/[\W_]/', $newPassword)) {
            $errorMessages[] = "Password must contain at least one letter, one number, and one special character.";
        }

        // Proceed with password change if no errors
        if (empty($errorMessages)) {
            // Fetch the current password hash from the database
            $stmt = $conn->prepare("SELECT PasswordHash FROM Users WHERE Email = ?");
            $stmt->bind_param("s", $username);  // Assuming the 'username' is stored as the Email
            $stmt->execute();
            $stmt->bind_result($storedPasswordHash);
            $stmt->fetch();
            $stmt->close();

            // Check if the entered current password is correct
            if (!password_verify($currentPassword, $storedPasswordHash)) {
                $errorMessages[] = "Current password is incorrect.";
            } else {
                // Hash the new password
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update the password in the database
                $updateStmt = $conn->prepare("UPDATE Users SET PasswordHash = ? WHERE Email = ?");
                $updateStmt->bind_param("ss", $newPasswordHash, $username);

                if ($updateStmt->execute()) {
                    $successMessage = "Password updated successfully!";
                } else {
                    $errorMessages[] = "Error updating password. Please try again.";
                }

                $updateStmt->close();
            }
        }

        // Close the database connection
        $conn->close();
    }
?>

<!doctype HTML>

<html>  
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LitSphere</title>
        <link rel="icon" href="/favicon/favicon.ico">
        <link rel="stylesheet" href="reader.css ">        
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="script.js"></script>
    </head>
    <body>        
        <div class="parent">
            <nav class="nav_container">
                <ul>
                    <img src="/nav_icon/Logo and Name.svg" alt="Logo & Name" style="width: 200px; height: 90px; margin-bottom: 25px; margin-top: 25px;">
                    <li>
                        <a href="bookprev.php">
                            <img src="/nav_icon/Home Icon.svg" alt="Home">
                            <span class="nav_item">Home</span> 
                        </a>                        
                    </li>
                    <li>
                        <a href="mylib.php">
                            <img src="/nav_icon/Library Icon.svg" alt="Library">
                            <span class="nav_item">My Library</span>
                        </a>
                    </li>
                    <li>
                        <a href="history.php">
                            <img src="/nav_icon/History Icon.svg" alt="History">
                            <span class="nav_item">History</span>
                        </a>
                    </li>
                    <li>
                        <a href="profile.php" class="active">
                            <img src="/nav_icon/Profile Icon.svg" alt="Profile">
                            <span class="nav_item">
                                <?php 
                                    if (isset($_SESSION['fname'])) {
                                        echo htmlspecialchars($_SESSION['fname']);
                                    } else {
                                        echo "Guest"; // Or some default text if the session variable is not set
                                    }
                                ?>
                            </span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="content_wrapper">
                <div class="content_container">
                    <div class="current_page">
                        <h3>Manage Account</h3>
                    </div>                     
                    <!-- diri and butanganan-->                        
                    <div class="main-cont">
                        <div class="pass-holder">
                            <button class="showBtn">
                                <i class="fa-solid fa-chevron-right" style="font-size: .9rem; margin-right: .8rem;"></i>
                                <p>Change password</p>
                            </button>                            
                        </div>
                        <div class="dropdown-content">
                            <p>Your password must be at least 6 characters and should include a combination of numbers, letters, and special characters (!$@%).</p>
                            <?php if (!empty($successMessage)): ?>
                                <p class="success-message" style="color: green;"><?php echo $successMessage; ?></p>
                            <?php endif; ?>

                            <?php if (!empty($errorMessages)): ?>
                                <ul class="error-messages" style="color: red;">
                                    <?php foreach ($errorMessages as $message): ?>
                                        <li><?php echo $message; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            <form action="profile.php" method="post">
                                <input type="password" id="current_password" name="current_password" placeholder=" Enter Password" class="inputbox" required><br>
                                <input type="password" id="new_password" name="new_password" placeholder=" Enter New Password" class="inputbox" required><br>
                                <input type="password" id="confirm_password" name="confirm_password" placeholder=" Re-Enter New Password" class="inputbox" required><br>
                                <button type="submit" id="savePassBtn">Save Password</button>
                            </form>                          
                        </div>
                        <div class="perso-holder">
                            <button class="showBtn">
                                <i class="fa-solid fa-chevron-right" style="font-size: .9rem; margin-right: .8rem;"></i>
                                <p>Personal Details</p>
                            </button>                            
                        </div>  
                        <div class="dropdown-content">                       
                        </div>
                    </div>
                    <div class="saveChangesBtn">
                        <form action="logout.php" method="post">
                            <button type="submit" id="logoutBtn">Log Out</button>
                        </form>
                    </div>                    
                    <!-- diri end -->                    
                </div>
                <footer>
                    <hr>
                    <p>Copyrights &#169; 2024 Litsphere. All Rights Reserved.</p>
                    <div class="iContainer">
                        <a href="#"><img src="/footer_icon/Facebook Logo.png" alt="Facebook Logo"></a>
                        <a href="#"><img src="/footer_icon/Twitter Logo.png" alt="Twitter Logo"></a>
                        <a href="#"><img src="/footer_icon/Instagram Logo.png" alt="Instagram Logo"></a>
                    </div>        
                </footer> 
            </div>            
        </div>
        <script>
            const dropdownBtns = document.querySelectorAll('.showBtn');
            const contents = document.querySelectorAll('.dropdown-content');

            dropdownBtns.forEach((btn, index) => {
                btn.addEventListener('click', function() {
                    const content = contents[index];
                    const icon = btn.querySelector('i'); // Select the icon inside the button
                    const isVisible = content.style.display === 'block';
                    
                    // Toggle content visibility
                    content.style.display = isVisible ? 'none' : 'block';
                    
                    // Toggle the icon class
                    if (isVisible) {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-right');
                    } else {
                        icon.classList.remove('fa-chevron-right');
                        icon.classList.add('fa-chevron-down');
                    }
                });
            });
        </script>
    </body>
</html>