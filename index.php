<?php
    include('connection.php');
?>

<!doctype HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LitSphere</title>
        <link rel="icon" href="/favicon/favicon.ico">
        <link rel="stylesheet" href="myStyle.css">
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body>
        <div class="parent">
            <div class="user_auth_container">
                <div id="logo">
                    <img src="Logo and Name.svg" alt="LitSphere Logo" style="height: 120px; width: 400px;">
                </div>
                <div class="user_auth">
                    <div class="login">
                        <p>Log in</p>
                    </div>
                    <div class="sign_up">
                        <p>Sign up</p>
                    </div>
                </div>             
            </div>
            <div class="slogan_container">
                <div id="slogan">
                    <h3>Literary Sphere:<br> Your Gateway to<br> Endless Reads</h3>
                </div>
                <div id="frontImg_container">
                    <img src="frontPage.png" alt="Book" style="height: 420px; width: 430px;">
                </div>                
            </div> 
        </div>
        <div class="login_wrapper">
            <div class="login_container">
                <form id="loginForm">
                    <i class="fa-solid fa-xmark"></i>
                    <h2 id="login">Log in</h2>
                    <label for="email">Email</label>    
                    <input type="text" id="login_email" required name="email">
                    <label for="password">Password</label>
                    <div class="password_container">
                        <input type="password" id="password" required name="password">
                        <i class="fa-regular fa-eye-slash"></i>
                    </div>      
                    <div id="login_error">
                        
                    </div>
                    <button type="submit" id="loginBtn" value="Log In">Log in</button>
                    <a href="#">Forgot password?</a>
                </form>                      
                <div id="bookimg">
                    <img src="Book Image.png" alt="Book Image">
                </div>   
            </div>
            <div class="signup_container">            
                <form method="post" id="signupForm">
                    <i class="fa-solid fa-xmark"></i>
                    <h2 id="signup">Sign up</h2>
                    <label for="firstname">First Name</label>
                    <input type="text" id="first_name" name="firstname">
                    <label for="lastname">Last Name</label>
                    <input type="text" id="last_name" name="lastname">
                    <label for="email">Email</label>    
                    <input type="text" id="signup_email" name="email" required name="email">
                    <label for="password">Password</label>
                    <input type="password" id="pass" required name="password">
                    <label for="confirm_password">Re-enter Password</label>
                    <input type="password" id="conf_pass" name="confirm_password" required>                    
                    <button type="submit" id="signupBtn" value="Sign Up" name="signUp">Sign up</button>
                </form>                      
                <div id="bookimg">
                    <img src="Book Image.png" alt="Book Image">
                </div>   
            </div>    
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
        <script>
            const loginBtn = document.querySelector('.login');

            loginBtn.addEventListener('click', function() {
                document.querySelector('.login_container').style.display = 'flex';
            });

            const signupBtn = document.querySelector('.sign_up')

            signupBtn.addEventListener('click', function() {
                document.querySelector('.signup_container').style.display = 'flex';
            });

            const closeButtons = document.querySelectorAll('.fa-xmark');

            closeButtons.forEach(function(closeButton) {
                closeButton.addEventListener('click', function() {
                    document.querySelector('.login_container').style.display = 'none';
                    document.querySelector('.signup_container').style.display = 'none';
                });
            });

            // Toggle password visibility
            const eyeIcon = document.querySelector('.fa-eye-slash');
            const passwordInput = document.getElementById('password');
            
            eyeIcon.addEventListener('click', function() {  
                if (passwordInput.getAttribute('type') === 'password') {
                    passwordInput.setAttribute('type', 'text');
                    eyeIcon.classList.replace("fa-eye-slash", "fa-eye");
                } else {
                    passwordInput.setAttribute('type', 'password');
                    eyeIcon.classList.replace("fa-eye", "fa-eye-slash");
                }
            });


            // AJAX
            $('#signupForm').submit(function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Collect form data
                var formData = $(this).serialize();

                // Send data to signup.php via AJAX
                $.ajax({
                    type: 'POST',
                    url: 'signup.php',
                    data: formData,
                    dataType: 'json',  // Expect a JSON response
                    success: function(response) {
                        console.log(response);  // Log the response to check the returned JSON
                        if (response.status === 'success') {
                            alert(response.message);  // Show success alert
                            $('#signupForm')[0].reset();  // Reset form fields
                        } else {
                            alert(response.message);  // Show error alert with the message
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Error: " + textStatus + ", " + errorThrown);
                        alert("An error occurred while processing your request.");
                    }
                });
            });

            $('#loginForm').submit(function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Reset previous error states (in case of multiple attempts)
                $('#login_email').removeClass('invalid_field');
                $('#password').removeClass('invalid_field');
                $('#login_error').hide();  // Hide the error message

                // Collect form data
                var formData = $(this).serialize() + "&logIn=true";  // Add 'logIn' field to the serialized data

                // Send data to login.php via AJAX
                $.ajax({
                    type: 'POST',
                    url: 'login.php',
                    data: formData,
                    dataType: 'json',  // Expect a JSON response
                    success: function(response) {
                        if (response.status === 'success') {
                            // Redirect to the appropriate page
                            window.location.href = response.redirectUrl;
                        } else {
                            // Show error message below the password field
                            $('#login_error').text(response.message).show();

                            // Add red borders to invalid email and password fields
                            $('#login_email').addClass('invalid_field');
                            $('#password').addClass('invalid_field');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Error: " + textStatus + ", " + errorThrown);
                        alert("An error occurred while processing your request.");
                    }
                });
            });
        </script>
    </body>
</html>