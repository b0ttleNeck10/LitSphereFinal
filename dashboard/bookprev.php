<?php
    session_start();
    date_default_timezone_set('Asia/Manila');
    include('../connection.php');

    if (!isset($_SESSION['username'])) {
        header("Location: ../index.php"); // Redirect to login if not logged in
        exit();
    }

    $userID = $_SESSION['userID'];

    // Query to check if the user is suspended
    $suspendedQuery = $conn->prepare("SELECT IsSuspended, SuspensionDate, SuspensionDuration, SuspensionReason FROM Users WHERE UserID = ?");
    $suspendedQuery->bind_param('i', $userID);
    $suspendedQuery->execute();
    $suspendedResult = $suspendedQuery->get_result();

    // Default values for suspension
    $isSuspended = false;
    $suspensionReason = '';
    $suspensionRemaining = '';

    if ($row = $suspendedResult->fetch_assoc()) {
        $isSuspended = $row['IsSuspended'];
        $suspensionReason = $row['SuspensionReason'];
        
        if ($isSuspended) {
            // Calculate remaining suspension time
            $currentTimestamp = time();
            $suspensionStartDate = strtotime($row['SuspensionDate']);
            $suspensionDurationInSeconds = ($row['SuspensionDuration']*24*60*60);
            $suspensionEndDate = $suspensionStartDate + $suspensionDurationInSeconds;
            $remainingTime = $suspensionEndDate - $currentTimestamp;

            if ($remainingTime > 0) {
                $daysRemaining = floor($remainingTime / (60 * 60 * 24));
                $hoursRemaining = floor(($remainingTime % (60 * 60 * 24)) / (60 * 60));
                $suspensionRemaining = "Remaining: $daysRemaining days $hoursRemaining hours";
            } else {
                // Suspension has ended, update the suspension status
                $isSuspended = false;
            }
        }
        
    }

    // Prepare and execute query to get all available books
    $booksQuery = $conn->prepare("SELECT * FROM Books WHERE Status = 'Available'");
    $booksQuery->execute();
    $booksResult = $booksQuery->get_result();
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
        <link href='https://fonts.googleapis.com/css?family=Schibsted Grotesk' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body>
        <?php if ($isSuspended): ?>
            <div class="suspendedAccWrapper" style="visibility: visible;">
                <div class="suspendedAccContainer">
                    <div class="suspendedSignContainer">
                        <img src="../verif_icon/suspended.svg" alt="Suspended">
                        <h1>SUSPENDED</h1>
                    </div>
                    <div class="reasonAndDateContainer">
                        <p id="suspendReason">You were suspended because <br> <?php echo htmlspecialchars($suspensionReason); ?></p>
                        <p><?php echo htmlspecialchars($suspensionRemaining); ?></p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- If not suspended, don't display the wrapper -->
            <div class="suspendedAccWrapper" style="visibility: hidden;"></div>
        <?php endif; ?>   
        <div class="parent">
            <nav class="nav_container">
                <ul>
                    <img src="../nav_icon/Logo and Name.svg" alt="Logo & Name" style="width: 209px; height: 65px; margin-top: 1.5rem; margin-bottom: 2rem;">
                    <li>
                        <a href="bookprev.php" class="active">
                            <img src="../nav_icon/Home Icon.svg" alt="Home">
                            <span class="nav_item">Home</span> 
                        </a>                        
                    </li>
                    <li>
                        <a href="mylib.php">
                            <img src="../nav_icon/Library Icon.svg" alt="Library">
                            <span class="nav_item">My Library</span>
                        </a>
                    </li>
                    <li>
                        <a href="history.php">
                            <img src="../nav_icon/History Icon.svg" alt="History">
                            <span class="nav_item">History</span>
                        </a>
                    </li>
                    <li>
                        <a href="profile.php">
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
                    <div class="search_cat_wrapper">
                        <div class="search_container">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" id="search_bar" placeholder="Search books" oninput="searchBooks()">
                        </div>
                        <div class="cat_container">
                            <button class="dropBtn">
                                <p>Category</p>
                                <i class="fa-solid fa-chevron-down"></i>
                            </button>
                        </div>
                    </div>
                    <div class="categories">
                        <a href="#" onclick="filterByCategory('Action')"><p>Action</p></a>
                        <a href="#" onclick="filterByCategory('Adventure')"><p>Adventure</p></a>
                        <a href="#" onclick="filterByCategory('Comedy')"><p>Comedy</p></a>
                        <a href="#" onclick="filterByCategory('Crime')"><p>Crime</p></a>
                        <a href="#" onclick="filterByCategory('Drama')"><p>Drama</p></a>
                        <a href="#" onclick="filterByCategory('Fantasy')"><p>Fantasy</p></a>
                        <a href="#" onclick="filterByCategory('Historical')"><p>Historical</p></a>
                        <a href="#" onclick="filterByCategory('Horror')"><p>Horror</p></a>
                        <a href="#" onclick="filterByCategory('Romance')"><p>Romance</p></a>
                        <a href="#" onclick="filterByCategory('Science Fiction')"><p>Science Fiction</p></a>
                        <a href="#" onclick="filterByCategory('Thriller')"><p>Thriller</p></a>
                    </div>
                    <h1 class="categoryName" style="display: none;">{the clicked category should be here}</h1>
                    <div class="book_wrapper">
                        <div class="no_book"><p>There's no book that matches your search</p></div>
                        <div class="book_container" id="book_container">
                            <?php while ($book = $booksResult->fetch_assoc()): ?>
                                <div class="book" onclick="openBookDetails('<?php echo htmlspecialchars($book['CoverImageURL']); ?>', '<?php echo htmlspecialchars($book['Title']); ?>', '<?php echo htmlspecialchars($book['AuthorName']); ?>', '<?php echo htmlspecialchars($book['Description']); ?>', '<?php echo htmlspecialchars($book['Genre']); ?>', '<?php echo $book['BookID']; ?>')">
                                    <img class="bookbook" src="<?php echo htmlspecialchars($book['CoverImageURL']); ?>" alt="Book Cover" style="height: 205px; width: 139px;">
                                    <p id="button1">Read Now!</p>
                                </div>
                            <?php endwhile; ?>                     
                        </div>                        
                    </div>
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
            <div class="popupbook" style="display: none;">
                <div class="popupbook-content">
                    <i class="fa-solid fa-xmark" id="close"></i>
                    <div class="imagecont">
                        <img src="" class="modal-image" alt="Book Cover"> <!-- Placeholder for dynamic content -->
                        <div class="textcontpopup">
                            <h1 class="BookTitle modal-title"></h1> <!-- Placeholder for dynamic content -->
                            <div class="bookinfo">
                                <h2 class="bookinf">Author</h2>
                                <h3 class="bookinf modal-author"></h3> <!-- Placeholder for dynamic content -->
                                <h2 class="bookinf">Genre</h2>
                                <h3 class="bookinf modal-genre"></h3>
                                <h2 class="bookinf">Description</h2>
                                <h3 class="bookinf modal-description"></h3> <!-- Placeholder for dynamic content -->
                                <a href="bookpage.php?book_id=<?php echo htmlspecialchars($book['BookID']); ?>" class="seemore">See more</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>         
        </div>
        <script>
            let typingTimer;
            const typingInterval = 200; // Adjust delay (in milliseconds)

            function searchBooks() {
                clearTimeout(typingTimer); // Clear any existing timer to debounce the input
                typingTimer = setTimeout(function() {
                    const searchQuery = document.getElementById('search_bar').value.trim(); // Trim whitespace to handle accidental spaces

                    // If search input is empty, load all books
                    if (searchQuery === "") {
                        loadAllBooks(); // Fetch and display all books
                    } else {
                        performSearch(searchQuery); // Perform the search query
                    }
                }, typingInterval);
            }

            // Function to perform the search when user types
            function performSearch(query) {
                document.querySelector('.categoryName').style.display = 'none';
                document.querySelector('.book_wrapper').style.marginTop = '2rem';

                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'search_books.php?query=' + encodeURIComponent(query), true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const bookContainer = document.getElementById('book_container');
                        const noBookMessage = document.querySelector('.no_book'); // Get the no_book message div

                        // Update book container with the search results
                        bookContainer.innerHTML = xhr.responseText;

                        // Show or hide the "No books found" message
                        if (xhr.responseText.trim() === '') {
                            noBookMessage.style.display = 'flex';  // Show the "No books found" message
                        } else {
                            noBookMessage.style.display = 'none';   // Hide the message if books are found
                        }
                    }
                };
                xhr.send();
            }

            // Function to load all books when the search input is cleared
            function loadAllBooks() {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'fetch_books.php', true); // Fetch all available books from the server
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const bookContainer = document.getElementById('book_container');
                        const noBookMessage = document.querySelector('.no_book'); // Get the no_book message div

                        // Update the book container with the full list of books
                        bookContainer.innerHTML = xhr.responseText;

                        // Hide the "No books found" message if books are present
                        noBookMessage.style.display = 'none';
                    }
                };
                xhr.send();
            }

            function filterByCategory(category) {
                const categoryName = document.querySelector('.categoryName');
                categoryName.style.display = 'flex';
                categoryName.textContent = category;

                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'fetch_books_by_category.php?category=' + encodeURIComponent(category), true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const bookContainer = document.getElementById('book_container');
                        const noBookMessage = document.querySelector('.no_book'); // Get the no_book message div
                        const bookWrapper = document.querySelector('.book_wrapper');

                        // Update book container with the filtered books
                        bookContainer.innerHTML = xhr.responseText;

                        // Show or hide the "No books found" message
                        if (xhr.responseText.trim() === '') {
                            categoryName.style.display = 'none';
                            noBookMessage.style.display = 'flex';  // Show the "No books found" message
                        } else {
                            categoryName.style.display = 'flex';
                            bookWrapper.style.marginTop = '.5rem';
                            noBookMessage.style.display = 'none';   // Hide the message if books are found
                        }
                    }
                };
                xhr.send();
            }

            const dropdownBtn = document.querySelector('.dropBtn');
            const categories = document.querySelector('.categories');

            dropdownBtn.addEventListener('click', function() {
                const isVisible = categories.style.visibility === 'visible';
                categories.style.visibility = isVisible ? 'hidden' : 'visible';
            });

            window.addEventListener('click', function(event) {
                const target = event.target;
                if (!dropdownBtn.contains(target)) {
                    categories.style.visibility = 'hidden';
                }
            });

            function openBookDetails(imageURL, title, author, description, genre, bookID) {
                console.log("Opening book details:", title); // Debug line
                document.querySelector('.modal-image').src = imageURL; // Assuming you have this element
                document.querySelector('.modal-title').textContent = title;
                document.querySelector('.modal-author').textContent = author;
                document.querySelector('.modal-description').textContent = description;
                document.querySelector('.modal-genre').textContent = genre; // Genre
                document.querySelector('.popupbook').style.display = 'flex';

                // Update the "See more" link with the book ID
                const seeMoreLink = document.querySelector('.seemore');
                seeMoreLink.href = `bookpage.php?book_id=${bookID}`;
            }

            // Close modal function
            document.querySelector('#close').onclick = function() {
                document.querySelector('.popupbook').style.display = 'none';
            };
        </script>  
    </body>
</html>