<?php
// add_comment.php
session_start();
include '../includes/config.php'; // DB connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $user_id = $_SESSION['user_id'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    $sql = "INSERT INTO comments (post_id, user_id, comment) VALUES ('$post_id', '$user_id', '$comment')";

    if (mysqli_query($conn, $sql)) {
        header('Location: post.php?id=' . $post_id);
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}
