<?php
    session_start();
    include('../connection.php');  // Ensure the connection is correct

    if (!isset($_SESSION['username'])) {
        header("Location: ../index.php");
        exit();
    }

    // Get the user ID from the session
    $username = $_SESSION['username'];

    // Fetch the UserID from the database based on the username
    $sql_user = "SELECT UserID FROM Users WHERE Email = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("s", $username);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows > 0) {
        $row_user = $result_user->fetch_assoc();
        $userID = $row_user['UserID'];
    } else {
        echo "User not found.";
        exit();
    }

    // Query to get the borrowed books for the logged-in user, filtered by Active or Overdue status
    $sql = "SELECT 
                b.BookID, 
                b.Title, 
                b.CoverImageURL, 
                CASE 
                    WHEN br.DueDate < CURDATE() THEN 'Overdue' 
                    ELSE 'Active' 
                END AS Status, 
                br.DueDate 
            FROM Borrow br
            JOIN Books b ON br.BookID = b.BookID
            WHERE br.UserID = ? 
            AND (br.Status = 'Active' OR br.DueDate < CURDATE())";
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
                                        <div class="book3" data-book-id="<?php echo htmlspecialchars($book['BookID']); ?>">
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
                        <div class="book_wrapper" id="bookSuggestion">
                            <div class="book_container">
                                <div class="book">
                                    <a href="#">
                                        <img src="../book_img/image1.svg">
                                        <p>Read Now!</p>
                                    </a>
                                </div>
                                <div class="book">
                                    <a href="#">
                                        <img src="../book_img/image1.svg">
                                        <p>Read Now!</p>
                                    </a>
                                </div>                  
                            </div>
                        </div>    
                    </div>
                    <button id="returnButton">Return</button>
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
            <div class="return_wrapper">
                <div class="table_container">
                    <div class="table_content">
                        <?php if (count($borrowedBooks) > 0): ?>
                            <table style="width: 100%;">
                                <tr>
                                    <th>Book Name</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                </tr>
                                <?php foreach ($borrowedBooks as $book): ?>
                                    <tr class="borrowed-book" data-book-id="<?= htmlspecialchars($book['BookID']); ?>">
                                        <td><?= htmlspecialchars($book['Title']); ?></td>
                                        <td><?= htmlspecialchars($book['Status']); ?></td>
                                        <td><?= htmlspecialchars($book['DueDate']); ?></td>
                                        <td style="width: 20px">
                                            <input type="checkbox" class="return-check"/>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        <?php else: ?>
                            <p>No borrowed books at the moment.</p>
                        <?php endif; ?>
                    </div>
                    <button id="returnBtn">Return</button>
                    <button id="closeReturn">Close</button>
                </div>                
            </div>
        </div>
        <script>
            document.getElementById('returnButton').addEventListener('click', function() {
                const returnWrapper = document.querySelector('.return_wrapper');
                
                // Show the return wrapper (modal) when the button is clicked
                returnWrapper.style.display = 'flex';
            });

            // Second Return Button: Handles the actual return process
            document.getElementById('returnBtn').addEventListener('click', function() {
                const selectedBooks = document.querySelectorAll('.return-check:checked');
                
                if (selectedBooks.length === 0) {
                    alert('Please select at least one book to return.');
                    return;
                }

                const bookIds = Array.from(selectedBooks).map(checkbox => {
                    return checkbox.closest('tr').getAttribute('data-book-id');
                });

                fetch('returnBook.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'book_ids': JSON.stringify(bookIds)
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);

                        // Optionally, remove the rows of the returned books
                        selectedBooks.forEach(checkbox => {
                            const row = checkbox.closest('tr');
                            row.remove();  // Remove the row for each returned book
                        });

                        // Reload the page to reflect the updated borrowed books list
                        location.reload(); // This will reload the entire page
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error during fetch:', error);
                    alert('An error occurred while returning the book. Please try again.');
                });
            });

            // Close Return Modal
            document.getElementById('closeReturn').addEventListener('click', function() {
                const returnWrapper = document.querySelector('.return_wrapper');
                returnWrapper.style.display = 'none';  // Close the modal
            });

            document.addEventListener('DOMContentLoaded', () => {
                const slides = document.querySelector('.slides');
                const prevButton = document.querySelector('.prev_btn');
                const nextButton = document.querySelector('.next_btn');
                const books = document.querySelectorAll('.book3');  // Make sure we select the books first
                let currentIndex = 0;
                
                // Ensure books are available before calculating slide width
                if (books.length > 0) {
                    const slideWidth = books[0].clientWidth;  // Take the width of the first book

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
                } else {
                    console.error('No books available for the slider.');
                }
            });
        </script>                      
    </body>
</html>
