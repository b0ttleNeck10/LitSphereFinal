<?php
    include('../connection.php');

    $sql = "SELECT * FROM Books";
    $result = mysqli_query($conn, $sql);

    // If there are no books, send a message
    if (mysqli_num_rows($result) == 0) {
        echo '<p>No books found.</p>';
        exit;
    }

    // If there are books, display them in HTML format
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
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
        <?php
    }
?>
