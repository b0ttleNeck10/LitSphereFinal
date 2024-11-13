<?php
    session_start();
    include('../connection.php');

    // Get the category from the query string
    $category = isset($_GET['category']) ? $_GET['category'] : '';

    // Sanitize the input to prevent SQL injection
    $category = htmlspecialchars($category);

    // Prepare SQL query to fetch books by category
    if (!empty($category)) {
        $stmt = $conn->prepare("SELECT * FROM Books WHERE Genre = ? AND Status = 'Available'");
        $stmt->bind_param('s', $category);
    } else {
        // If no category is provided, show all books
        $stmt = $conn->prepare("SELECT * FROM Books WHERE Status = 'Available'");
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch and output the books as HTML
    if ($result->num_rows > 0) {
        while ($book = $result->fetch_assoc()) {
            ?>
                <div class="book" onclick="openBookDetails('<?php echo htmlspecialchars($book['CoverImageURL']); ?>', '<?php echo htmlspecialchars($book['Title']); ?>', '<?php echo htmlspecialchars($book['AuthorName']); ?>', '<?php echo htmlspecialchars($book['Description']); ?>', '<?php echo htmlspecialchars($book['Genre']); ?>', '<?php echo $book['BookID']; ?>')">
                    <img class="bookbook" src="<?php echo htmlspecialchars($book['CoverImageURL']); ?>" alt="Book Cover" style="height: 205px; width: 139px;">
                    <p id="button1">Read Now!</p>
                </div>
            <?php
        }
    }

    $stmt->close();
    $conn->close();
?>
