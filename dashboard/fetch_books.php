<?php
    session_start();
    include('../connection.php');

    // Ensure the user is logged in
    if (!isset($_SESSION['fname'])) {
        echo "You must be logged in to view books.";
        exit();
    }

    // Prepare and execute query to get all available books
    $booksQuery = $conn->prepare("SELECT * FROM Books WHERE Status = 'Available'");
    $booksQuery->execute();
    $booksResult = $booksQuery->get_result();

    // Generate HTML for each book
    while ($book = $booksResult->fetch_assoc()) {
        echo '
        <div class="book" onclick="openBookDetails(\'' . htmlspecialchars($book['CoverImageURL']) . '\', \'' . htmlspecialchars($book['Title']) . '\', \'' . htmlspecialchars($book['AuthorName']) . '\', \'' . htmlspecialchars($book['Description']) . '\', \'' . htmlspecialchars($book['Genre']) . '\', ' . $book['BookID'] . ')">
            <img src="' . htmlspecialchars($book['CoverImageURL']) . '" alt="Book Cover">
            <p id="button1">Read Now!</p>
        </div>';
    }
?>
