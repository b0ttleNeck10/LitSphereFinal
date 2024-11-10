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
        <script src="script.js"></script>
    </head>
    <body>        
        <div class="parent">
            <nav class="nav_container">
                <ul>
                    <img src="../nav_icon/Logo and Name.svg" alt="Logo & Name" style="width: 200px; height: 90px; margin-bottom: 25px; margin-top: 25px;">
                    <li>
                        <a href="notification.php" class="active">
                            <img src="../nav_icon/Notification Icon.svg" alt="Home">
                            <span class="nav_item">Notifications</span> 
                        </a>                        
                    </li>
                    <li>
                        <a href="bookinv.php">
                            <img src="../nav_icon/Library Icon.svg" alt="Library">
                            <span class="nav_item">My Library</span>
                        </a>
                    </li>
                    <li>
                        <a href="inventory.php"> 
                            <img src="../nav_icon/Reader Icon.svg" alt="History">
                            <span class="nav_item">Reader</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="../nav_icon/Profile Icon.svg" alt="Profile">
                            <span class="nav_item">Profile</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="content_wrapper">
                <div class="content_container_admin">
                    <div class="current_page_admin">
                        <h3>Book Inventory</h3>
                        <div class="search_container_admin">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" id="search_bar" placeholder="Search books">
                        </div>   
                    </div>

                    <!-- book holder -->
                    <div class="book_container">
                        <img src="../book_img/walk.png" alt="Book Image" class="book_image">
                        <div class="book_item">
                            <div class="book_info">
                                <h4 class="book_title">Walk Into The Shadow</h4>
                                <p class="book_author">Estelle Darcy | Drama, Sci-Fi</p>
                            </div>
                            <div class="book_actions">
                                <button class="edit_btn" onclick="showEditPopup()">
                                    <img src="../button_img/Edit.svg" alt="Edit" class="action_icon">
                                </button>
                                <button class="delete_btn">
                                    <img src="../button_img/Remove.svg" alt="Delete" class="action_icon">
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Add Book Button -->
                    <button class="add_book_button" onclick="showPopup()">
                        Add Book
                    </button>       
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
        <!-- Edit book -->
        <div class="editPopupbg">        
            <div class="editPopup">
                <button class="closeEdit-button" onclick="closeEditPopup()">✖</button>
                <div class="edit-form-content">
                    <div class="edit-image-upload">
                        <img src="book_img/walk.png" alt="walk">
                    </div>
                    <div class="edit-form-fields">
                        <label for="book-title">Book Title</label>
                        <input type="text" id="Book-title" name="bTitle" class="editbookinput" value="Walk Into The Shadow">
                        
                        <label for="author">Author</label>
                        <input type="text" id="Book-author" name="bAuthor" class="editbookinput" value="Estelle Darcy">
                        
                        <label for="description">Description</label>
                        <textarea id="Book-Desc" name="bDesc" class="editbookdesc">Shadow people are some of the most mysterious entities in the known universe, and Mike Ricksecker has experienced many, starting with a tall, dark humanoid figure that appeared in his room as a child.</textarea>
        
                        <button class="save-button" onclick="saveChanges()">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add book  -->
        <div class="popupaddbg">
            <div class="popupadd">
                <button class="close-button" onclick="closePopup()">✖</button>
                <div class="form-content">
                    <div class="image-upload">
                        <div class="image-placeholder">Click to Add Image</div>
                    </div>
                    <div class="form-fields">
                        <label for="book-title">Book Title</label>
                        <input type="text" id="Book-title" name="bTitle" class="bookinput">   
                        <label for="author">Author</label>
                        <input type="text" id="Book-author" name="bAuthor" class="bookinput">
                        <label for="genre">Genre</label>
                        <select id="genre" class="genre-select">
                            <option value="action">Action</option>
                            <option value="drama">Drama</option>
                            <option value="romance">Romance</option>
                        </select>           
                        <label for="description">Description</label>
                        <textarea id="Book-Desc" name="bDesc" class="bookdesc"></textarea>
                        <button class="add-book-btn" onclick="addBook()">Add Book</button>
                    </div>
                </div>
            </div>
        </div>
        
        

        <script>
        // addbook
        function showPopup() {
        document.querySelector('.popupaddbg').style.visibility = 'visible'
        }

        function closePopup() {
        document.querySelector('.popupaddbg').style.visibility = 'hidden'
        }
        function addBook() {
        document.querySelector('.popupaddbg').style.visibility = 'hidden'
        }

        // editbook
        function showEditPopup() {
        document.querySelector('.editPopupbg').style.visibility = 'visible'
        }

        function closeEditPopup() {
        document.querySelector('.editPopupbg').style.visibility = 'hidden'
        }

        function saveChanges() {
        document.querySelector('.editPopupbg').style.visibility = 'hidden'
        }







        </script>
    </body>
</html>