<?php
    session_start();
    include('../connection.php');

    // Check if user is logged in
    if (!isset($_SESSION['fname'])) {
        header("Location: ../index.php"); // Redirect to login if not logged in
        exit();
    }

    $username = $_SESSION['username']; // Assuming username is stored in session

    $conn->close();
?>
 

<!doctype HTML>

<html>  
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LitSphere</title>
        <link rel="icon" href="../favicon/favicon.ico">
        <link rel="stylesheet" href="reader.css ">        
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body>        
        <div class="parent">
        <nav class="nav_container">
                <ul>
                    <img src="../nav_icon/Logo and Name.svg" alt="Logo & Name" style="width: 209px; height: 65px; margin-top: 1.5rem; margin-bottom: 2rem;">
                    <li>
                        <a href="notification.php">
                            <img src="../nav_icon/Notification Icon.svg" alt="Home">
                            <span class="nav_item">Notifications</span> 
                        </a>                        
                    </li>
                    <li>
                        <a href="inventory.php">
                            <img src="../nav_icon/Library Icon.svg" alt="Library">
                            <span class="nav_item">Inventory</span>
                        </a>
                    </li>
                    <li>
                        <a href="readers.php" class="active">
                            <img src="../nav_icon/Reader Icon.svg" alt="History">
                            <span class="nav_item">Reader</span>
                        </a>
                    </li>
                    <li>
                        <a href="adminprofile.php">
                            <img src="../nav_icon/Profile Icon.svg" alt="Profile">
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
                        <h3>Admin Account</h3>
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
                        <a href="#"><img src="../footer_icon/Facebook Logo.png" alt="Facebook Logo"></a>
                        <a href="#"><img src="../footer_icon/Twitter Logo.png" alt="Twitter Logo"></a>
                        <a href="#"><img src="../footer_icon/Instagram Logo.png" alt="Instagram Logo"></a>
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