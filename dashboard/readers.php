<?php
    session_start();
    include('../connection.php');

    // Ensure the user is logged in (if needed)
    if (!isset($_SESSION['fname'])) {
        header("Location: ../index.php");
        exit();
    }

    // Fetch all users from the Users table except those with the admin role
    $sql = "SELECT * FROM Users WHERE Role != 'Admin'"; // Ensure that 'Role' is the column storing user roles
    $result = mysqli_query($conn, $sql);

    // Close the connection
    mysqli_close($conn);
?>

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
                        <a href="inventory.php">
                            <img src="../nav_icon/Library Icon.svg" alt="Library">
                            <span class="nav_item">Inventory</span>
                        </a>
                    </li>
                    <li>
                        <a href="readers.php" class="active">
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
                        <h3>Readers</h3>
                    </div>
                    <div class="reader_wrapper">
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
							<?php if ($row['Email'] === 'admin@example.com') continue; // Skip this iteration ?>
							<div class="reader_container">
								<a href="managereaderacc.php?userid=<?php echo $row['UserID']; ?>">
									<img src="../reader_img/userImg.png" alt="User Profile Picture">
									<p><?php echo $row['FirstName'] . ' ' . $row['LastName']; ?></p>
								</a>
								<?php if ($row['IsSuspended']): ?>
									<div class="suspended">
										<p>Suspended</p>
									</div>
								<?php endif; ?>
							</div>
						<?php endwhile; ?>                              
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
        </div>
    </body>
</html>
