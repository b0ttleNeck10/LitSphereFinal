<?php
    session_start();
    include('../connection.php');

    // Check if the user is logged in
    if (!isset($_SESSION['fname'])) {
        $_SESSION['fname'] = $user['FirstName']; // Or however you set the session data
        header("Location: ../index.php");
        exit();
    }

    // Get the user ID from the query string
    if (isset($_GET['userid'])) {
        $userID = $_GET['userid'];

        // Fetch user details from the database
        $sql = "SELECT * FROM Users WHERE UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userID); // Bind the user ID as an integer
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Fetch the user's details
            $user = $result->fetch_assoc();
        } else {
            echo "User not found.";
            exit();
        }
        
        // Close the statement
        $stmt->close();

        // Fetch borrowing history for this user
        $borrowHistorySQL = "
            SELECT bh.BookID, bo.Title, bh.BorrowDate, bh.ReturnDate, DATEDIFF(COALESCE(bh.ReturnDate, CURDATE()), bh.BorrowDate) AS BorrowDuration
            FROM BorrowingHistory bh
            LEFT JOIN Books bo ON bh.BookID = bo.BookID
            WHERE bh.UserID = ?

            UNION

            SELECT br.BookID, bo.Title, br.BorrowDate AS BorrowDate, br.ReturnDate, DATEDIFF(COALESCE(br.ReturnDate, br.DueDate), br.BorrowDate) AS BorrowDuration
            FROM Borrow br
            LEFT JOIN Books bo ON br.BookID = bo.BookID
            WHERE br.UserID = ?
            AND br.Status != 'Returned'
        ";

        $stmt = $conn->prepare($borrowHistorySQL);
        $stmt->bind_param("ii", $userID, $userID); // Bind the user ID for both parts of the query
        $stmt->execute();
        $borrowResult = $stmt->get_result();
        
        $borrowHistory = [];
        while ($row = $borrowResult->fetch_assoc()) {
            $borrowHistory[] = $row;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "No user selected.";
        exit();
    }

    // Query to count the number of books in the user's library
    $countBooksSQL = "
        SELECT COUNT(*) AS bookCount
        FROM BorrowingHistory bh
        WHERE bh.UserID = ? AND bh.ReturnDate IS NULL
        UNION
        SELECT COUNT(*) AS bookCount
        FROM Borrow br
        WHERE br.UserID = ? AND br.Status != 'Returned'
    ";

    $stmt = $conn->prepare($countBooksSQL);
    $stmt->bind_param("ii", $userID, $userID); // Bind the user ID for both parts of the query
    $stmt->execute();
    $countResult = $stmt->get_result();
    $bookCount = 0;
    if ($countResult->num_rows > 0) {
        $countRow = $countResult->fetch_assoc();
        $bookCount = $countRow['bookCount']; // Get the count of borrowed books
    }

    $stmt->close();

    // Close the connection
    mysqli_close($conn);
?>

<!doctype HTML>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LitSphere</title>
        <link rel="icon" href="../favicon/favicon.ico">
        <link rel="stylesheet" href="reader.css">
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                        <a href="inventory.php" class="active">
                            <img src="../nav_icon/Library Icon.svg" alt="Library">
                            <span class="nav_item">Inventory</span>
                        </a>
                    </li>
                    <li>
                        <a href="readers.php"> 
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
                        <i class="fa-solid fa-chevron-left fa-2x"></i>
                        <h3>Readers/Hapeh Bertday</h3>
                    </div>
                    <!--manage account page-->               
                    <div class="account_page">
                        <div class="accountinf">
                            <img src="../reader_img/userImg.png" alt="">                    
                        </div>       
                        <div class="account_deets">
                            <div class="accdeets">
                                <div class="account_row">
                                    <h6 class="dbold">Name:</h6><h6 class="dlight"><?php echo $user['FirstName'] . ' ' . $user['LastName']; ?></h6>
                                </div>
                                <div class="account_row">
                                    <h6 class="dbold">Email:</h6><h6 class="dlight"><?php echo $user['Email']; ?></h6>
                                </div>
                                <div class="account_row">
                                    <h6 class="dbold">Status:</h6><h6 class="dlight" id="userStatus"><?php echo $user['IsSuspended'] ? 'Suspended' : 'Active'; ?></h6>
                                </div>
                                <div class="account_row">
                                    <h6 class="dbold">Library Books:</h6><h6 class="dlight"><?php echo $bookCount; ?></h6>
                                </div>
                                <button class="suspend-btn" id="suspendclick">Suspend Account</button>
                            </div>
                        </div>
                    </div>
                    <div class="borrowhist">     
                        <h3>Borrow History</h3>
                    </div>
                    <div class="hist">
                        <?php if (empty($borrowHistory)): ?>
                            <p>This user hasn't added any book in his library yet.</p>
                        <?php else: ?>
                            <?php foreach ($borrowHistory as $history): ?>
                                <h6><?php echo $user['FirstName'] . ' ' . $user['LastName']; ?> added the book ‘<?php echo $history['Title']; ?>’ in his library for <?php echo $history['BorrowDuration']; ?> days.</h6>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- manage account page END -->
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
                <img src="../verif_icon/warning.png" alt="warn" class="swarn">
                <p>This account has been suspended for {duration} days.</p>
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

                // Get the userID (assume it is available in a variable or data attribute)
                const userID = <?php echo $user['UserID']; ?>;

                // Prepare the data for the AJAX request
                const data = {
                    userID: userID,
                    reason: reasonField.value.trim(),
                    days: suspensionDays.value
                };

                // Send AJAX request to suspend the account
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "suspend_user.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                // Handle the response from the server
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText); // Parse the response JSON
                        if (response.success) {
                            // Show confirmation popup
                            confirmationPopup.style.display = "flex";  
                            popupSuspendBg.style.visibility = "hidden"; // Hide the suspend form

                            // Update the confirmation message with the selected duration
                            const durationText = suspensionDays.value;
                            document.querySelector(".susconf p").textContent = `This account has been suspended for ${durationText} days.`;

                            // Update the user status text in the account section
                            document.getElementById('userStatus').textContent = "Suspended";
                        } else {
                            alert("Failed to suspend account. Please try again.");
                        }
                    } else {
                        alert("An error occurred. Please try again.");
                    }
                };

                // Serialize the form data
                const params = new URLSearchParams(data).toString();

                // Send the request
                xhr.send(params);
            });

            // Close the confirmation popup
            document.querySelector(".suspclose").addEventListener("click", function () {
                confirmationPopup.style.display = "none"; // Close the confirmation popup
            });
        </script>
        <!--  Pop Suspend Form  END -->
    </body>
</html>