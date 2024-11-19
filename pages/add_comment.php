<?php
// add_comment.php
include '../includes/config.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input data
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    // Insert the comment into the comments table with the username
    $sql = "INSERT INTO comments (post_id, username, comment, created_at) VALUES ('$post_id', '$username', '$comment', NOW())";

    // Execute the query and check for success
    if (mysqli_query($conn, $sql)) {
        // Redirect back to the specific blog post page after submitting the comment
        header('Location: ../html/readview.php?id=' . $post_id);
        exit();
    } else {
        // Display an error message if the query fails
        echo 'Error: ' . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);

