<?php
    session_start();
    include('../connection.php');
    
    // Ensure user is logged in
    if (!isset($_SESSION['fname'])) {
        $_SESSION['fname'] = $user['FirstName'];
        header("Location: ../index.php");
        exit();
    }
    
    // Fetch all books from the database
    $sql = "SELECT * FROM Books";
    $result = mysqli_query($conn, $sql);    
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
                    <div class="search_cat_wrapper">
                        <div class="current_page">
                            <h3>Book Inventory</h3>
                        </div>
                        <div class="search_container">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" id="search_bar" placeholder="Search books">
                        </div>
                    </div>
                    <div class="inventory_container">
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <div class="content" data-book-id="<?php echo $row['BookID']; ?>">
                                <img src="<?php echo $row['CoverImageURL']; ?>" alt="Book Cover">
                                <div class="content_desc">
                                    <div class="book_desc">
                                        <h4><?php echo $row['Title']; ?></h4>
                                        <p><?php echo $row['AuthorName']; ?> | <?php echo $row['Genre']; ?></p>
                                    </div>
                                    <i class="fa-regular fa-pen-to-square edit-icon" onclick="editBook(<?php echo $row['BookID']; ?>)"></i>
                                    <i class="fa-regular fa-trash-can delete-icon" onclick="deleteBook(<?php echo $row['BookID']; ?>)"></i>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <div class="no_book" style="display: none;">No books found.</div>
                    </div>
                    <button id="addBookBtn" onclick="showPopup()" type="submit" style="padding-left: 25px; padding-right: 25px; margin-bottom: 0;">Add book</button>            
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
                <div class="popupaddbg">
                    <div class="popupadd">
                        <i class="fa-solid fa-xmark" onclick="closePopup()" id="closeAddBook"></i>
                        <div class="form-content">
                            <form id="addBookForm" action="add_book.php" method="POST" enctype="multipart/form-data">
                                <label class="image-upload" for="myImg">
                                    <input type="file" id="myImg" name="myImg" hidden>
                                    <div class="image-placeholder">Click to Add Image</div>
                                </label>
                                <div class="form-container">
                                    <div class="form-fields">
                                        <label for="book-title">Book Title</label>
                                        <input type="text" id="Book-title" name="bTitle" class="bookinput">   
                                        <label for="author">Author</label>
                                        <input type="text" id="Book-author" name="bAuthor" class="bookinput">
                                        <label for="genre">Genre</label>
                                        <select id="genre" name="genre" class="genre-select">
                                            <option value="Action">Action</option>
                                            <option value="Adventure">Adventure</option>
                                            <option value="Comedy">Comedy</option>
                                            <option value="Crime">Crime</option>
                                            <option value="Drama">Drama</option>
                                            <option value="Fantasy">Fantasy</option>
                                            <option value="Historical">Historical</option>
                                            <option value="Romance">Romance</option>
                                            <option value="Science Fiction">Science Fiction</option>
                                            <option value="Thriller">Thriller</option>
                                        </select>           
                                        <label for="description">Description</label>
                                        <textarea id="Book-Desc" name="bDesc" class="bookdesc"></textarea>
                                    </div>
                                    <button class="add-book-btn" type="button" onclick="addBook()">Add Book</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="popupEditWrapper">
                    <div class="editContainer">
                        <i class="fa-solid fa-xmark" onclick="closePopup()" id="closeEditBook"></i>
                        <div class="form-content">
                            <form id="editBookForm" action="edit_book.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="bookID" id="editBookID" value="">
                                <label class="edit-image-upload" for="editMyImg">
                                    <input type="file" id="editMyImg" name="editMyImg" hidden>
                                    <div class="edit-image-placeholder">Click to Add Image</div>
                                </label>
                                <div class="edit-form-container">
                                    <div class="edit-form-fields">
                                        <input type="text" id="editBook-title" name="editBTitle" class="bookinput">
                                        <input type="text" id="editBook-author" name="editBAuthor" class="bookinput">
                                        <select id="editGenre" name="editGenre" class="genre-select">
                                            <option value="Action">Action</option>
                                            <option value="Adventure">Adventure</option>
                                            <option value="Comedy">Comedy</option>
                                            <option value="Crime">Crime</option>
                                            <option value="Drama">Drama</option>
                                            <option value="Fantasy">Fantasy</option>
                                            <option value="Historical">Historical</option>
                                            <option value="Romance">Romance</option>
                                            <option value="Science Fiction">Science Fiction</option>
                                            <option value="Thriller">Thriller</option>
                                        </select>
                                        <textarea id="editBook-Desc" name="editBDesc" class="bookdesc"></textarea>
                                    </div>
                                    <button type="submit" class="edit-book-btn">Edit Book</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="popupDeleteWrapper">
                    <div class="deleteContainer">
                        <p>Are you sure you want to delete?</p>
                        <div class="decisionBtn">
                            <button id="yesBtn">Yes</button>
                            <button id="noBtn">No</button>    
                        </div>                        
                    </div>
                </div>
            </div>            
        </div>
        <script>
            applyStaggeredAnimations();

            // Function to apply staggered animation delay
            function applyStaggeredAnimations() {
                const contents = document.querySelectorAll('.content'); // Get all the content elements

                // Loop through each .content and apply the animation delay
                contents.forEach((content, index) => {
                    content.style.animationDelay = `${index * 0.3}s`; // Apply delay based on index
                });
            }

            let typingTimer;
            const typingInterval = 200; // Adjust delay (in milliseconds)

            // Event listener for search input
            document.getElementById('search_bar').addEventListener('input', searchBooks);

            // Search function
            function searchBooks() {
                clearTimeout(typingTimer); // Clear any existing timer to debounce the input
                typingTimer = setTimeout(function() {
                    const searchQuery = document.getElementById('search_bar').value.trim(); // Get the search query and remove extra spaces

                    // If the search query is empty, load all books
                    if (searchQuery === "") {
                        loadAllBooks(); // Fetch and display all books
                    } else {
                        performSearch(searchQuery); // Perform the search
                    }
                }, typingInterval);
            }

            // Function to perform the search
            function performSearch(query) {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'search_books_admin.php?query=' + encodeURIComponent(query), true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const bookContainer = document.querySelector('.inventory_container');
                        let noBookMessage = document.querySelector('.no_book'); // Get the no_book message div

                        // If noBookMessage doesn't exist, create it dynamically
                        if (!noBookMessage) {
                            noBookMessage = document.createElement('div');
                            noBookMessage.classList.add('no_book');
                            noBookMessage.innerText = 'No books found.';
                            noBookMessage.style.display = 'none'; // Initially hide the message
                            bookContainer.appendChild(noBookMessage); // Append to the book container
                        }

                        // Clear the current books in the container
                        bookContainer.innerHTML = '';

                        const response = xhr.responseText.trim();

                        if (response === '' || response === '<p>No books found.</p>') {
                            noBookMessage.style.display = 'flex';  // Show the "No books found" message if no books are found
                        } else {
                            noBookMessage.style.display = 'none';   // Hide the message if books are found
                            bookContainer.innerHTML = response;     // Inject search results into the container
                        }

                        // Apply staggered animations after injecting content
                        applyStaggeredAnimations();
                    }
                };
                xhr.send();
            }

            // Function to load all books when the search input is cleared
            function loadAllBooks() {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'fetch_book_admin.php', true); // Fetch all available books from the server
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const bookContainer = document.querySelector('.inventory_container');
                        let noBookMessage = document.querySelector('.no_book'); // Get the no_book message div

                        // If noBookMessage doesn't exist, create it dynamically
                        if (!noBookMessage) {
                            noBookMessage = document.createElement('div');
                            noBookMessage.classList.add('no_book');
                            noBookMessage.innerText = 'No books found.';
                            noBookMessage.style.display = 'none'; // Initially hide the message
                            bookContainer.appendChild(noBookMessage); // Append to the book container
                        }

                        // Clear the current books in the container
                        bookContainer.innerHTML = '';

                        const response = xhr.responseText.trim();

                        if (response === '') {
                            noBookMessage.style.display = 'flex';  // Show the "No books found" message if no books are found
                        } else {
                            noBookMessage.style.display = 'none';   // Hide the message if books are found
                            bookContainer.innerHTML = response;     // Inject all books into the container
                        }

                        // Apply staggered animations after injecting content
                        applyStaggeredAnimations();
                    }
                };
                xhr.send();
            }

            function editBook(bookID) {
                console.log("Book ID: " + bookID);  // Log the book ID for debugging

                // Check if the bookID is undefined or null
                if (!bookID) {
                    console.error("No book ID provided!");
                    return;  // Exit if no book ID is provided
                }

                // Create a new AJAX request to fetch book details
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "fetch_book_details.php?bookID=" + bookID, true);

                xhr.onload = function () {
                    if (xhr.status === 200) {
                        // Check if the response is valid
                        var book = JSON.parse(xhr.responseText);
                        if (book && book.Title) {
                            document.getElementById('editBookID').value = book.BookID;
                            document.getElementById('editBook-title').value = book.Title;
                            document.getElementById('editBook-author').value = book.AuthorName;
                            document.getElementById('editGenre').value = book.Genre;
                            document.getElementById('editBook-Desc').value = book.Description;

                            // If the book has a cover image, display it
                            if (book.CoverImageURL) {
                                const editShowImg = document.querySelector('.edit-image-upload');
                                editShowImg.style.backgroundImage = `url(${book.CoverImageURL})`;
                                editShowImg.style.backgroundSize = 'cover';
                                editShowImg.style.backgroundPosition = 'center';
                            }

                            // Show the edit popup
                            document.querySelector('.popupEditWrapper').style.visibility = 'visible';
                        } else {
                            console.error("Invalid response from the server");
                        }
                    } else {
                        console.error("Error fetching book details: " + xhr.status);
                    }
                };

                xhr.send();
            }

            document.getElementById('editBookForm').onsubmit = function(event) {
                event.preventDefault();  // Prevent normal form submission

                // Prepare the form data using FormData
                var formData = new FormData(this);

                // Create a new AJAX request to update the book
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'edit_book.php', true);

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert(response.message);
                            closePopup();  // Close the edit popup
                            location.reload();  // Reload the page to show updated details
                        } else {
                            alert('Error: ' + response.message);
                        }
                    } else {
                        alert('Request failed. Please try again.');
                    }
                };

                // Send the form data via AJAX
                xhr.send(formData);
            };



            function showPopup() {
                document.querySelector('.popupaddbg').style.visibility = 'visible';
            }

            const showImg = document.querySelector('.image-upload');
            const upload = document.getElementById('myImg');

            upload.addEventListener('change', function () {
                const reader = new FileReader();
                reader.readAsDataURL(upload.files[0]);
                reader.onload = ()=> {
                    console.log(reader.result);
                    showImg.style.backgroundImage = `url(${reader.result})`; // Corrected to reader.result
                    showImg.style.backgroundSize = 'cover';
                    showImg.style.backgroundRepeat = 'no-repeat';
                    showImg.style.backgroundPosition = 'center';
                    document.querySelector('.image-placeholder').style.display = 'none';
                }
            });

            function addBook() {
                // Prevent the form from submitting normally
                event.preventDefault();

                // Prepare the form data using FormData
                var formData = new FormData(document.getElementById('addBookForm'));

                // Create the AJAX request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'add_book.php', true);

                // Set up the response handler
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);

                        // Check if the response is successful
                        if (response.status === 'success') {
                            alert(response.message);  // Show success message
                            closePopup();  // Close the popup
                            location.reload();  // Reload the page to show the newly added book
                        } else {
                            alert('Error: ' + response.message);  // Show error message
                        }
                    } else {
                        alert('Request failed. Please try again.');
                    }
                };

                // Send the form data via AJAX
                xhr.send(formData);
            }

            /// Get all the edit and delete icons
            const editIcons = document.querySelectorAll('.edit-icon');
            const deleteIcons = document.querySelectorAll('.delete-icon');

            // Function to show the edit popup
            function showEditPopup() {
                // Show the popup
                document.querySelector('.popupEditWrapper').style.visibility = 'visible';
            }

            // Function to close the edit popup
            function closeEditPopup() {
                document.querySelector('.popupEditWrapper').style.visibility = 'hidden';
            }

            // Function to switch the class for the edit icon
            function switchEditIconClass(event) {
                event.target.classList.toggle('fa-regular');
                event.target.classList.toggle('fa-solid');
            }

            // Function to switch the class for the delete icon
            function switchDeleteIconClass(event) {
                event.target.classList.toggle('fa-regular');
                event.target.classList.toggle('fa-solid');
            }

            // Add event listeners for each edit icon (mouseover and mouseout)
            editIcons.forEach(icon => {
                icon.addEventListener('mouseover', switchEditIconClass);
                icon.addEventListener('mouseout', switchEditIconClass);

                // Add event listener for click to show the edit popup
                icon.addEventListener('click', showEditPopup);
            });

            // Add event listeners for each delete icon (mouseover and mouseout)
            deleteIcons.forEach(icon => {
                icon.addEventListener('mouseover', switchDeleteIconClass);
                icon.addEventListener('mouseout', switchDeleteIconClass);
            });

            function deleteBook(bookID) {
                // Store the book ID (for later use when confirming the delete action)
                document.getElementById('yesBtn').onclick = function() {

                    // Send an AJAX request to delete the book from the database
                    var xhr = new XMLHttpRequest();
                    xhr.open("GET", "delete_book.php?bookID=" + bookID, true);

                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            // Handle the server's response (e.g., success message)
                            alert('Book deleted successfully');
                            location.reload();  // Reload the page to reflect the changes
                        } else {
                            console.error("Error deleting the book");
                        }
                    };

                    xhr.send();

                    // Hide the delete popup after confirmation
                    document.querySelector('.popupDeleteWrapper').style.visibility = 'hidden';
                };

                // Show the delete confirmation popup
                document.querySelector('.popupDeleteWrapper').style.visibility = 'visible';
            }

            document.getElementById('noBtn').onclick = function() {
                document.querySelector('.popupDeleteWrapper').style.visibility = 'hidden';
            };

            // Add event listeners to each delete icon to show the delete confirmation popup
            deleteIcons.forEach(icon => {
                icon.addEventListener('click', function() {
                    // Get the book ID from the closest parent div with the data-book-id attribute
                    var bookID = icon.closest('.content').getAttribute('data-book-id');
                });
            });

            // Similar event listener for the edit popup
            const editShowImg = document.querySelector('.edit-image-upload');
            const editUpload = document.getElementById('editMyImg');

            editUpload.addEventListener('change', function () {
                const file = editUpload.files[0]; // Get the first file from the input
                if (file) {
                    const reader = new FileReader();
                    reader.readAsDataURL(file);  // Read the file as a data URL (base64)
                    reader.onload = () => {
                        console.log(reader.result);
                        editShowImg.style.backgroundImage = `url(${reader.result})`;  // Set the background image to the uploaded file
                        editShowImg.style.backgroundSize = 'cover';
                        editShowImg.style.backgroundRepeat = 'no-repeat';
                        editShowImg.style.backgroundPosition = 'center';
                        document.querySelector('.edit-image-placeholder').style.display = 'none';  // Hide the placeholder
                    };
                } else {
                    // If no file is selected, reset the image preview and show placeholder
                    resetFileInputAndPreview('.edit-image-upload', '#editMyImg');
                }
            });

            function closePopup() {
                document.querySelector('.popupaddbg').style.visibility = 'hidden';
                document.querySelector('.popupEditWrapper').style.visibility = 'hidden';
                
                resetFileInputAndPreview('.image-upload', '#myImg');
                resetFileInputAndPreview('.edit-image-upload', '#editMyImg');
            }

            function resetFileInputAndPreview(uploadSelector, fileInputSelector) {
                // Reset the file input
                const uploadInput = document.querySelector(fileInputSelector);
                uploadInput.value = '';  // Clears the file input

                // Reset the background image and show the placeholder
                const uploadWrapper = document.querySelector(uploadSelector);
                uploadWrapper.style.backgroundImage = ''; // Remove background image
                uploadWrapper.style.backgroundSize = '';
                uploadWrapper.style.backgroundRepeat = '';
                uploadWrapper.style.backgroundPosition = '';

                const placeholder = uploadWrapper.querySelector('.image-placeholder') || uploadWrapper.querySelector('.edit-image-placeholder');
                if (placeholder) {
                    placeholder.style.display = 'block';  // Show the placeholder text again
                }
            }
        </script>
    </body>
</html>