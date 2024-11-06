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
                        <a href="home.html" class="active">
                            <img src="nav_icon/Home Icon.svg" alt="Home">
                            <span class="nav_item">Home</span> 
                        </a>                        
                    </li>
                    <li>
                        <a href="mylib.html">
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
                    <div class="search_cat_wrapper">
                        <div class="search_container">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" id="search_bar" placeholder="Search books">
                        </div>
                        <div class="cat_container">
                            <button class="dropBtn">
                                <p>Category</p>
                                <i class="fa-solid fa-chevron-down"></i>
                                <div class="categories">
                                    <a href="#"><p>Action</p></a>
                                    <a href="#"><p>Adventure</p></a>
                                    <a href="#"><p>Comedy</p></a>
                                    <a href="#"><p>Crime</p></a>
                                    <a href="#"><p>Drama</p></a>
                                    <a href="#"><p>Fantasy</p></a>
                                    <a href="#"><p>Historical</p></a>
                                    <a href="#"><p>Horror</p></a>
                                    <a href="#"><p>Romance</p></a>
                                    <a href="#"><p>Science Fiction</p></a>
                                    <a href="#"><p>Thriller</p></a>
                                </div>
                            </button>
                        </div>
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
                            <div class="book">
                                <a href="#">
                                    <img src="book_img/image1.svg">
                                    <p>Read Now!</p>
                                </a>
                            </div>                            
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
        </div>
        <script>
            //Catergory 
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
        </script>
    </body>
</html>