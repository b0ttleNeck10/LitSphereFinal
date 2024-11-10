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
$passwordErrorMessages = [];
$passwordSuccessMessage = '';
$detailErrorMessages = [];
$detailSuccessMessage = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle password change form submission
    if (isset($_POST['submit_password'])) {
        $currentPassword = isset($_POST['current_password']) ? $_POST['current_password'] : '';
        $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
        $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

        // Password validation checks
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $passwordErrorMessages[] = "All fields are required.";
        }

        if ($newPassword !== $confirmPassword) {
            $passwordErrorMessages[] = "New password and confirmation do not match.";
        }

        if (strlen($newPassword) < 6) {
            $passwordErrorMessages[] = "Password must be at least 6 characters long.";
        }

        if (!preg_match('/[A-Za-z]/', $newPassword) || !preg_match('/\d/', $newPassword) || !preg_match('/[\W_]/', $newPassword)) {
            $passwordErrorMessages[] = "Password must contain at least one letter, one number, and one special character.";
        }

        if (empty($passwordErrorMessages)) {
            // Fetch the current password hash from the database
            $stmt = $conn->prepare("SELECT PasswordHash FROM Users WHERE Email = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($storedPasswordHash);
            $stmt->fetch();
            $stmt->close();

            if (!password_verify($currentPassword, $storedPasswordHash)) {
                $passwordErrorMessages[] = "Current password is incorrect.";
            } else {
                // Hash the new password and update the database
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateStmt = $conn->prepare("UPDATE Users SET PasswordHash = ? WHERE Email = ?");
                $updateStmt->bind_param("ss", $newPasswordHash, $username);

                if ($updateStmt->execute()) {
                    $passwordSuccessMessage = "Password updated successfully!";
                } else {
                    $passwordErrorMessages[] = "Error updating password. Please try again.";
                }

                $updateStmt->close();
            }
        }
    }

    // Handle personal details form submission
    if (isset($_POST['submit_details'])) {
        $newFirstName = isset($_POST['new_firstname']) ? $_POST['new_firstname'] : '';
        $newLastName = isset($_POST['new_lastname']) ? $_POST['new_lastname'] : '';
        $newEmail = isset($_POST['new_email']) ? $_POST['new_email'] : '';

        // Personal details validation checks
        if (empty($newFirstName) || empty($newLastName) || empty($newEmail)) {
            $detailErrorMessages[] = "All fields are required.";
        }

        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $detailErrorMessages[] = "Invalid email format.";
        }

        if (empty($detailErrorMessages)) {
            // Update personal details in the database
            $updateStmt = $conn->prepare("UPDATE Users SET FirstName = ?, LastName = ?, Email = ? WHERE Email = ?");
            $updateStmt->bind_param("ssss", $newFirstName, $newLastName, $newEmail, $username);

            if ($updateStmt->execute()) {
                // Update session variables if email changed
                $_SESSION['fname'] = $newFirstName;
                $_SESSION['lname'] = $newLastName;
                $_SESSION['username'] = $newEmail; // Update username if email is changed

                $detailSuccessMessage = "Personal details updated successfully!";
            } else {
                $detailErrorMessages[] = "Error updating personal details. Please try again.";
            }

            $updateStmt->close();
        }
    }

    // Return the error/success messages as JSON
    echo json_encode([
        'passwordErrors' => $passwordErrorMessages,
        'passwordSuccess' => $passwordSuccessMessage,
        'detailsErrors' => $detailErrorMessages,
        'detailsSuccess' => $detailSuccessMessage
    ]);
    exit;
}

// Close the database connection
$conn->close();
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                            <div id="password-error" style="color: red; margin-left: 1.5rem;"></div>
                            <div id="password-success" style="color: green; margin-left: 1.5rem;"></div>
                            <form id="password-form">
                                <input type="password" id="current_password" name="current_password" placeholder=" Enter Password" class="inputbox" required><br>
                                <input type="password" id="new_password" name="new_password" placeholder=" Enter New Password" class="inputbox" required><br>
                                <input type="password" id="confirm_password" name="confirm_password" placeholder=" Re-Enter New Password" class="inputbox" required><br>
                                <button type="submit" name="submit_password" id="savePassBtn">Save Password</button>
                            </form>
                        </div>
                        <div class="perso-holder">
                            <button class="showBtn">
                                <i class="fa-solid fa-chevron-right" style="font-size: .9rem; margin-right: .8rem;"></i>
                                <p>Personal Details</p>
                            </button>
                        </div>
                        <div class="dropdown-content">
                            <div id="details-error" style="color: red; margin-left: 1.5rem;"></div>
                            <div id="details-success" style="color: green; margin-left: 1.5rem;"></div>
                            <form id="details-form">
                                <input type="text" id="new_firstname" name="new_firstname" placeholder=" Enter First Name" class="inputbox" required><br>
                                <input type="text" id="new_lastname" name="new_lastname" placeholder=" Enter Last Name" class="inputbox" required><br>
                                <input type="text" id="new_email" name="new_email" placeholder=" Enter Email" class="inputbox" required><br>
                                <button type="submit" name="submit_details" id="saveDetails">Save Details</button>
                            </form>
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
            $(document).ready(function() {
                // Handle password form submission
                $('#password-form').on('submit', function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize(); // Get form data

                    $.ajax({
                        type: 'POST',
                        url: 'profile.php',
                        data: formData + '&submit_password=true',
                        dataType: 'json',
                        success: function(response) {
                            if (response.passwordErrors.length > 0) {
                                $('#password-error').html('<ul>' + response.passwordErrors.map(function(err) {
                                    return '<li>' + err + '</li>';
                                }).join('') + '</ul>');
                                $('#password-success').html('');
                            } else {
                                $('#password-success').html(response.passwordSuccess);
                                $('#password-error').html('');
                            }
                        }
                    });
                });

                // Handle details form submission
                $('#details-form').on('submit', function(e) {
                    e.preventDefault();
                    var formData = $(this).serialize(); // Get form data

                    $.ajax({
                        type: 'POST',
                        url: 'profile.php',
                        data: formData + '&submit_details=true',
                        dataType: 'json',
                        success: function(response) {
                            if (response.detailsErrors.length > 0) {
                                $('#details-error').html('<ul>' + response.detailsErrors.map(function(err) {
                                    return '<li>' + err + '</li>';
                                }).join('') + '</ul>');
                                $('#details-success').html('');
                            } else {
                                $('#details-success').html(response.detailsSuccess);
                                $('#details-error').html('');
                            }
                        }
                    });
                });
            });
            const dropdownBtns = document.querySelectorAll('.showBtn');
            const contents = document.querySelectorAll('.dropdown-content');
            const forms = document.querySelectorAll('.dropdown-content form');

            dropdownBtns.forEach((btn, index) => {
                btn.addEventListener('click', function() {
                    // Check if the form is being submitted. If yes, don't toggle the dropdown
                    if (forms[index].contains(event.target)) {
                        // Prevent the default behavior (form submission)
                        return;
                    }

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

            // Listen for form submission to keep the dropdown visible if there are validation errors
            forms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    // Check if the form has validation errors
                    const formErrors = form.querySelectorAll('.error-messages');
                    if (formErrors.length > 0) {
                        // Prevent form from submitting and closing the dropdown if there are errors
                        event.preventDefault();
                        event.stopPropagation();

                        // Optionally, you could also highlight the errors or display a message
                    }
                });
            });
        </script>
    </body>
</html>