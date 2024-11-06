<!doctype HTML>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LitSphere</title>
        <link rel="icon" href="favicon/favicon.ico">
        <link rel="stylesheet" href="reader.css">
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body>
        <div class="parent">
            <nav class="nav_container">
                <ul>
                    <img src="nav_icon/Logo and Name.svg" alt="Logo & Name" style="width: 200px; height: 90px; margin-bottom: 25px; margin-top: 25px;">
                    <li>
                        <a href="home.html">
                            <img src="nav_icon/Home Icon.svg" alt="Home">
                            <span class="nav_item">Home</span> 
                        </a>                        
                    </li>
                    <li>
                        <a href="mylibrary.html">
                            <img src="nav_icon/Library Icon.svg" alt="Library">
                            <span class="nav_item">My Library</span>
                        </a>
                    </li>
                    <li>
                        <a href="history.html">
                            <img src="nav_icon/History Icon.svg" alt="History">
                            <span class="nav_item">History</span>
                        </a>
                    </li>
                    <li>
                        <a href="profile.html">
                            <img src="nav_icon/Profile Icon.svg" alt="Profile">
                            <span class="nav_item">Profile</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="content_wrapper">
                <div class="content_container">         
                    <div class="current_page">
                        <i class="fa-solid fa-chevron-left fa-2x"></i>
                        <h3>Readers/Hapeh Bertday</h3>
                    </div>
                    <!--manage account page-->               
                    <div class="account_page">
                        <div class="accountinf">
                            <img src="reader_img/1.jfif" alt="">                    
                        </div>       
                        <div class="account_deets">
                            <div class="accdeets">
                                <div class="account_row">
                                    <h6 class="dbold">Name:</h6><h6 class="dlight">Hape Bertday</h6>
                                </div>
                                <div class="account_row">
                                    <h6 class="dbold">Email:</h6><h6 class="dlight">hapehbertday@gmail.com</h6>
                                </div>
                                <div class="account_row">
                                    <h6 class="dbold">Phone:</h6><h6 class="dlight">12345678901</h6>
                                </div>
                                <div class="account_row">
                                    <h6 class="dbold">Library Books:</h6><h6 class="dlight">hape Bertday</h6>
                                </div>
                                <button class="suspend-btn" id="suspendclick">Suspend Account</button>
                            </div>
                        </div>
                    </div>
                    <div class="borrowhist">     
                        <h3>Borrow History</h3>
                    </div>
                    <div class="hist">
                        <h6>Hapeh added the book ‘Don’t Look Back’ in his Library. </h6>
                        <h6>Hapeh added the book ‘It Ends With Us’ in his Library. </h6>
                        <h6>Hapeh added the book ‘Fantastic Beast: The Crimes of Gridelwald’ in his Library. </h6>
                    </div>
                </div>
                <!-- manage account page END -->
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
        <!--  Pop Suspend Form  -->
        <div class="popupsuspendbg">
            <div class="popupsuspend">
                <div class="xmarksus">
                    <i class="fa-solid fa-xmark"></i>
                </div>
                <h1>Suspend Account</h1>
                <div class="susformind">
                    <form class="susform" action="">
                        <label for="reason">Reason</label><br>
                        <textarea name="reason" id="reason"></textarea><br>
                        <label for="day">Day</label><br>
                        <select name="day" id="day_selection" required>
                            <option value="" disabled selected>Day</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                        </select>
                        <button id="submit_sus" type="submit">Suspend</button>
                    </form>
                </div>
            </div>
        </div>
        <!--Pop-up SUSPEND CONFIRMATION-->
        <div class="susconfbg">
            <div class="susconf">
                <h1>Account Suspended!</h1>                
                <img src="verif_icon/warning.png" alt="warn" class="swarn">
                <p>This account has been suspended for  n days.</p>
                <button class="suspclose">Close</button>
            </div>
        </div>
        <!-- SUSPEND POP-UP SCRIPT -->
        <script>            
            // Selectors for the key elements
            const suspendButton = document.getElementById("suspendclick");
            const popupSuspendBg = document.querySelector(".popupsuspendbg");
            const closeButton = document.querySelector(".fa-xmark");
            const submitButton = document.getElementById("submit_sus");
            const suspendForm = document.querySelector(".susform");
            const confirmationPopup = document.querySelector(".susconfbg");
            const suspensionDays = document.getElementById("day_selection");
            const reasonField = document.getElementById("reason");
            
            // Show the suspend form when "Suspend Account" button is clicked
            suspendButton.addEventListener("click", function () {
                popupSuspendBg.style.visibility = "visible";
            });

            // Close the form and go back to the main page when "X" is clicked
            closeButton.addEventListener("click", function () {
                popupSuspendBg.style.visibility = "hidden";
            });

            // When the form is submitted
            submitButton.addEventListener("click", function (event) {
                event.preventDefault(); // Prevent the form from submitting the traditional way

                // Check if the required fields are filled
                if (reasonField.value.trim() === "" || suspensionDays.value === "") {
                    alert("Please fill out all required fields.");
                    return; // Don't proceed if the fields are not filled
                }

                // Show the confirmation popup and hide the form background
                confirmationPopup.style.display = "flex";  // Show confirmation popup
                popupSuspendBg.style.visibility = "hidden"; // Hide the suspend form

                // Clear the form after submission
                suspendForm.reset();
            });

            // Close the confirmation popup
            document.querySelector(".suspclose").addEventListener("click", function () {
                confirmationPopup.style.display = "none"; // Close the confirmation popup
            });
        </script>
        <!--  Pop Suspend Form  END -->
    </body>
</html>