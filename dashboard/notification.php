<?php
    session_start();
    if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin@example.com') {
        header("Location: ../index.php"); // Redirect if not admin
        exit();
    }

    if (!isset($_SESSION['userID'])) {
        header("Location: login.php"); // Redirect to login if userID is not set
        exit();
    }

    include('../connection.php');

    // Fetch all active borrow requests
    $query = "SELECT Borrow.BorrowID, Borrow.BookID, Borrow.UserID, Borrow.BorrowDate, Borrow.DueDate, Users.FirstName, Users.LastName, Users.Email, Books.Title
            FROM Borrow
            JOIN Users ON Borrow.UserID = Users.UserID
            JOIN Books ON Borrow.BookID = Books.BookID
            WHERE Borrow.Status = 'Requested'";

    $result = $conn->query($query);

    // Initialize an array to store the borrow requests grouped by date
    $groupedRequests = [];

    if ($result->num_rows > 0) {
        // Group requests by BorrowDate
        while ($row = $result->fetch_assoc()) {
            $borrowDate = date('F j, Y', strtotime($row['BorrowDate']));
            if (!isset($groupedRequests[$borrowDate])) {
                $groupedRequests[$borrowDate] = [];
            }
            $groupedRequests[$borrowDate][] = $row;
        }
    }
?>

<!doctype HTML>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LitSphere</title>
        <link rel="icon" href="/favicon/favicon.ico">
        <link rel="stylesheet" href="reader.css">
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body>
        <div class="parent">
            <nav class="nav_container">
                <ul>
                    <img src="/nav_icon/Logo and Name.svg" alt="Logo & Name" style="width: 200px; height: 90px; margin-bottom: 25px; margin-top: 25px;">
                    <li>
                        <a href="notification.html" class="active">
                            <img src="/nav_icon/Notification Icon.svg" alt="Home">
                            <span class="nav_item">Notifications</span> 
                        </a>                        
                    </li>
                    <li>
                        <a href="inventory.php">
                            <img src="/nav_icon/Library Icon.svg" alt="Library">
                            <span class="nav_item">My Library</span>
                        </a>
                    </li>
                    <li>
                        <a href="readers.php"> 
                            <img src="/nav_icon/Reader Icon.svg" alt="History">
                            <span class="nav_item">Reader</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="/nav_icon/Profile Icon.svg" alt="Profile">
                            <span class="nav_item">Profile</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="content_wrapper">
                <div class="content_container">
                    <div class="current_page">
                        <h3>Notifications</h3>
                    </div> 
                    <div class="notif_contiainer">
                        <?php
                            // Loop through the grouped requests
                            foreach ($groupedRequests as $date => $requests) {
                                echo '<div class="notif_date">';
                                echo '<p>' . htmlspecialchars($date) . '</p>'; // Display the date only once
                                echo '</div>';

                                // Now display each individual request for that date
                                foreach ($requests as $row) {
                                    echo '<div class="notif">';
                                    echo '<p><strong>' . htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']) . '</strong> requested to borrow the book <strong>' . htmlspecialchars($row['Title']) . '</strong>.</p>';
                                    echo '<div class="btnContainer">';
                                    echo '<form action="approve_deny.php" method="POST">';
                                    echo '<input type="hidden" name="borrow_id" value="' . $row['BorrowID'] . '">';
                                    echo '<button type="submit" name="action" value="approve" class="approveBtn">Approve</button>';
                                    echo '<button type="submit" name="action" value="deny" class="denyBtn">Deny</button>';
                                    echo '</form>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            }
                        ?>
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
            </div>            
        </div>
        <script>
            document.getElementById('loginForm').addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(this);
                fetch('login.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        console.log("Redirecting to:", data.redirectUrl); // Debug: Check the redirect URL
                        window.location.href = data.redirectUrl; // Redirect to the URL received from the server
                    } else {
                        alert(data.message); // Show error message if the login failed
                    }
                })
                .catch(error => {
                    console.error('Error:', error); // Handle network or other errors
                });
            });
        </script>
    </body>
</html>