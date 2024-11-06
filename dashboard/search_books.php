<?php
    session_start();
    include('../connection.php');

    // Ensure user is logged in
    if (!isset($_SESSION['fname'])) {
        echo "You must be logged in to search.";
        exit();
    }

    // Check if query parameter exists
    if (isset($_GET['query'])) {
        // Sanitize the input to prevent SQL injection
        $searchQuery = $_GET['query'];
        $searchQuery = '%' . $conn->real_escape_string($searchQuery) . '%';

        // Prepare the query to search books by title or author
        $sql = "SELECT * FROM Books WHERE Status = 'Available' AND (Title LIKE ? OR AuthorName LIKE ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $searchQuery, $searchQuery);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if any results are found
        if ($result->num_rows > 0) {
            // Output books and assign the fade-in class
            while ($book = $result->fetch_assoc()) {
                echo '
                <div class="book" onclick="openBookDetails(\'' . htmlspecialchars($book['CoverImageURL']) . '\', \'' . htmlspecialchars($book['Title']) . '\', \'' . htmlspecialchars($book['AuthorName']) . '\', \'' . htmlspecialchars($book['Description']) . '\', \'' . htmlspecialchars($book['Genre']) . '\', ' . $book['BookID'] . ')">
                    <img src="' . htmlspecialchars($book['CoverImageURL']) . '" alt="Book Cover">
                    <p id="button1">Read Now!</p>
                </div>';
            }
        } else {
            echo "";
        }
    } else {
        echo "<p>Please enter a search query.</p>";
    }
?>
