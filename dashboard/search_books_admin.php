<?php
    include('../connection.php');

    if (isset($_GET['query'])) {
        $query = $_GET['query'];
        $sql = "SELECT * FROM Books WHERE Title LIKE '%$query%' OR AuthorName LIKE '%$query%' OR Genre LIKE '%$query%'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='content' data-book-id='{$row['BookID']}'>
                        <img src='{$row['CoverImageURL']}' alt='Book Cover'>
                        <div class='content_desc'>
                            <div class='book_desc'>
                                <h4>{$row['Title']}</h4>
                                <p>{$row['AuthorName']} | {$row['Genre']}</p>
                            </div>
                            <i class='fa-regular fa-pen-to-square edit-icon' onclick='editBook({$row['BookID']})'></i>
                            <i class='fa-regular fa-trash-can delete-icon' onclick='deleteBook({$row['BookID']})'></i>
                        </div>
                    </div>";
            }
        } else {
            echo "<p>No books found.</p>";
        }
    }
?>