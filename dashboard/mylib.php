<?php
    session_start();
    include('../connection.php');  // Make sure the connection is correct.
    
    if (!isset($_SESSION['username'])) {
        header("Location: ../index.php"); // Redirect to login if not logged in
        exit();
    }

    // Get the user ID from the session (assuming the session contains the logged-in user's info)
    $username = $_SESSION['username'];

    // Fetch the UserID from the database based on the username
    $sql_user = "SELECT UserID FROM Users WHERE Email = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("s", $username);  // Binding the username
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows > 0) {
        $row_user = $result_user->fetch_assoc();
        $userID = $row_user['UserID'];  // Store the UserID from the result
    } else {
        echo "User not found.";
        exit();
    }

    // Query to get the borrowed books for the logged-in user
    $sql = "SELECT b.CoverImageURL, b.Title 
            FROM Borrow br
            JOIN Books b ON br.BookID = b.BookID
            WHERE br.UserID = ? AND br.Status = 'Active'"; // Only active borrowings
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID); // Bind the userID as an integer
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the books
    $borrowedBooks = [];
    while ($row = $result->fetch_assoc()) {
        $borrowedBooks[] = $row;
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
                        <a href="bookprev.php">
                            <img src="/nav_icon/Home Icon.svg" alt="Home">
                            <span class="nav_item">Home</span> 
                        </a>                        
                    </li>
                    <li>
                        <a href="mylib.php" class="active">
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
                        <h3>My Library</h3>
                    </div>
                    <div class="slider_container">  
                        <button class="prev_btn"><i class="fa-solid fa-angle-left"></i></button>
                        <div class="slider">
                            <div class="slides">
                                <?php if (count($borrowedBooks) > 0): ?>
                                    <?php foreach ($borrowedBooks as $book): ?>
                                        <div class="book3">
                                            <a href="book_detail.php?title=<?php echo urlencode($book['Title']); ?>">
                                                <img src="<?php echo htmlspecialchars($book['CoverImageURL']); ?>" alt="<?php echo htmlspecialchars($book['Title']); ?>">
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <!-- <p>No borrowed books at the moment.</p> -->
                                <?php endif; ?>
                            </div>
                        </div>
                        <button class="next_btn"><i class="fa-solid fa-angle-right"></i></button>
                    </div>
                    <div class="suggestion_container">
                        <div class="suggestion">
                            <h4>Suggestions</h4>
                        </div>            
                        <div class="book_wrapper">
                            <div class="book_container">
                                <div class="book">
                                    <a href="#">
                                        <img src="book_img/image1.svg">
                                        <p>Read Now!</p>
                                    </a>
                                </div>
                                <div class="book">
                                    <a href="#">
                                        <img src="book_img/image1.svg">
                                        <p>Read Now!</p>
                                    </a>
                                </div>                  
                            </div>
                        </div>                                        
                    </div>
                </div>
                <button class="button-36" role="button">Return</button>
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
            const slides = document.querySelector('.slides');
            const prevButton = document.querySelector('.prev_btn');
            const nextButton = document.querySelector('.next_btn');
            let currentIndex = 0;
            const slideWidth = document.querySelector('.book3').clientWidth; // Including gap

            nextButton.addEventListener('click', () => {
                currentIndex++;
                if (currentIndex >= slides.children.length) {
                    currentIndex = 0;
                }
                slides.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
            });

            prevButton.addEventListener('click', () => {
                currentIndex--;
                if (currentIndex < 0) {
                    currentIndex = slides.children.length - 1;
                }
                slides.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
            });
        </script>                      
    </body>
</html>
