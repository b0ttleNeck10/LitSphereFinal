<?php
session_start();
include('../connection.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if userID is set in session
if (!isset($_SESSION['userID'])) {
    header("Location: login.php"); // Redirect to login if userID is not set
    exit();
}

// Get the book ID from the URL
$bookID = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;

// Fetch book details
$bookQuery = $conn->prepare("SELECT * FROM Books WHERE BookID = ?");
$bookQuery->bind_param("i", $bookID);
$bookQuery->execute();
$bookResult = $bookQuery->get_result();

if ($bookResult->num_rows === 0) {
    echo "Book not found.";
    exit();
}

$book = $bookResult->fetch_assoc();

// Handle the request book form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data from the form
    $userID = $_SESSION['userID']; // Get user ID from session
    $days = intval($_POST['day']);  // Get the days for the return date
    $purpose = $_POST['purpose'];   // Get the purpose of borrowing

    // Calculate dates
    $borrowDate = date('Y-m-d'); // Current date
    $dueDate = date('Y-m-d', strtotime("+$days days")); // Due date based on the number of days

    // Insert a new record into the Borrow table with 'Active' status
    $insertQuery = $conn->prepare("INSERT INTO Borrow (BookID, UserID, BorrowDate, DueDate, Status) VALUES (?, ?, ?, ?, 'Requested')");
    $insertQuery->bind_param("iiss", $bookID, $userID, $borrowDate, $dueDate);

    if ($insertQuery->execute()) {
        // Insert the borrowing history
        $historyQuery = $conn->prepare("INSERT INTO BorrowingHistory (UserID, BookID, BorrowDate) VALUES (?, ?, ?)");
        $historyQuery->bind_param("iis", $userID, $bookID, $borrowDate);
        $historyQuery->execute();

        // Update the book status to 'Borrowed' (indicating it has been requested)
        $updateQuery = $conn->prepare("UPDATE Books SET Status = 'Borrowed' WHERE BookID = ?");
        $updateQuery->bind_param("i", $bookID);
        $updateQuery->execute();

        // Redirect to the same page with a success query parameter
        header("Location: bookpage.php?book_id=$bookID&success=1");
        exit();
    } else {
        echo "Error processing your request.";
    }
}
?>

<!doctype HTML>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($book['Title']); ?></title>
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
                        <a href="bookprev.php" class="active">
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
                        <a href="profile.php">
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
                        <i class="fa-solid fa-chevron-left fa-2x"></i>
                        <h3>Home/It Ends With Us</h3>
                    </div>           
                    <div class="book_page">
                        <div class="req_book">
                            <img src="<?php echo htmlspecialchars($book['CoverImageURL']); ?>" alt="">      
                            <button id="requestButton">Request Book</button> 
                        </div>       
                        <div class="book_deets">
                            <h6 class="header">Title</h6>
                            <p><?php echo htmlspecialchars($book['Title']); ?></p>
                            <h6 class="header">Author</h6>
                            <p><?php echo htmlspecialchars($book['AuthorName']); ?></p>
                            <h6 class="header">Genre</h6>
                            <p><?php echo htmlspecialchars($book['Genre']); ?></p>
                            <h6 class="header">Description</h6>
                            <p><?php echo nl2br(htmlspecialchars($book['Description'])); ?></p>
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
            </div>
           <div class="request" style="display: none;">
                <div class="req_wrapper" style="display: flex;">
                    <i class="fa-solid fa-xmark" id="closeRequest"></i>
                    <div class="verif_container" style="visibility: hidden;">
                        <h4>Request Sent!</h4>
                        <img src="/verif_icon/success.svg" alt="Successful">
                        <p>Your book request was sent successfully!</p>
                    </div>
                    <div class="req_container">
                        <h4>Request Book</h4>
                        <form id="requestForm" method="POST" action="">
                            <label for="day">Return Date:</label><br>
                            <select name="day" id="day_selection" required>
                                <option value="" disabled selected>Day</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                            </select><br>
                            <label for="purpose">Purpose:</label><br>
                            <input type="radio" name="purpose" value="Referencing" id="referencing">
                            <label for="referencing">Referencing</label><br>
                            <input type="radio" name="purpose" value="In House Reading" id="in_house" checked>
                            <label for="in_house">In House Reading</label><br>
                            <button id="submit_req" type="submit">Request Book</button>
                        </form>
                    </div>                
                </div>          
           </div> 
        </div>
        <script>
            const requestButton = document.getElementById('requestButton');
            const reqWrapper = document.querySelector('.req_wrapper');
            const closeButton = document.getElementById('closeRequest');
            const verifContainer = document.querySelector('.verif_container');
            const request = document.querySelector('.request');

            // Show the request form when the request button is clicked
            requestButton.addEventListener('click', function() {
                request.style.display = 'flex';
                verifContainer.style.display = 'none'; // Reset the success message
            });

            // Close the request form when the close button is clicked
            closeButton.addEventListener('click', function() {
                request.style.display = 'none';
            });

            // Check if the request was successful by looking for a query parameter
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                verifContainer.style.display = 'block';
                reqWrapper.style.display = 'flex';
                document.querySelector('.req_container').style.visibility = 'hidden';
                document.querySelector('.verif_container').style.visibility = 'visible';
                document.querySelector('.verif_container').style.display = 'flex';
                
                // Disable the request button
                requestButton.disabled = true;
                requestButton.textContent = "Request Sent"; // Optionally change button text
            }
        </script>
    </body>
</html>