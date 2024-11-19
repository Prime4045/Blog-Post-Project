<?php
include '../includes/config.php'; // Include the database connection

// Check if the blog ID is provided
if (!isset($_GET['id'])) {
    die("Blog post not specified.");
}

// Retrieve the blog post by ID
$post_id = intval($_GET['id']);
$sql = "SELECT * FROM posts WHERE id = $post_id";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $post = mysqli_fetch_assoc($result);
} else {
    die("Blog post not found.");
}

// Fetch comments for this post without joining users table
$comments_sql = "SELECT comment, username, created_at FROM comments WHERE post_id = $post_id ORDER BY created_at DESC";
$comments_result = mysqli_query($conn, $comments_sql);

// Check for errors in the comments query
if (!$comments_result) {
    die("Error fetching comments: " . mysqli_error($conn));
}

// Fetch comments if the query was successful
$comments = mysqli_fetch_all($comments_result, MYSQLI_ASSOC);

// Helper function to calculate "time ago"
function timeAgo($time)
{
    $time_difference = time() - strtotime($time);
    if ($time_difference < 1) {
        return 'Just now';
    }
    $condition = array(
        12 * 30 * 24 * 60 * 60 => 'year',
        30 * 24 * 60 * 60 => 'month',
        24 * 60 * 60 => 'day',
        60 * 60 => 'hour',
        60 => 'minute',
        1 => 'second'
    );
    foreach ($condition as $secs => $str) {
        $d = $time_difference / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Full-width container */
        .container {
            padding: 40px 100px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
            position: relative;
        }

        /* Back to all blog posts link */
        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #007BFF;
            text-decoration: none;
            font-size: 1em;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* Blog post title */
        .blog-post h1 {
            font-size: 2.3em;
            color: #333;
            margin-bottom: 10px;
        }

        /* Author and date */
        .blog-post p {
            font-size: 1em;
            color: #666;
            margin-bottom: 20px;
        }

        /* Blog image */
        .blog-post img {
            width: 50%;
            max-height: 20%;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        /* Blog description heading */
        .description-heading {
            font-size: 1.5em;
            color: #333;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        /* Blog description */
        .blog-post-content {
            width: 95%;
            font-size: 1em;
            color: #555;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        /* Comments section */
        .comments-section {
            margin-top: 50px;
        }

        /* Comments title */
        .comments-section h3 {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 20px;
        }

        /* Individual comment */
        .comment {
            background-color: #fff;
            margin-top: 10px;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Comment username and date */
        .comment strong {
            color: #007BFF;
        }

        .comment span {
            font-size: 0.8em;
            color: #888;
        }

        /* Comment text */
        .comment p {
            font-size: 0.9em;
            color: #333;
            margin-top: 8px;
        }

        /* Comment form */
        .comment-form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
        }

        /* Input and Textarea for comment */
        .comment-form input,
        .comment-form textarea {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-family: "Ubuntu", sans-serif;
            margin-bottom: 10px;
            font-size: 1em;
            width: 100%;
            resize: none;
        }

        /* Submit button */
        .comment-form button {
            align-self: flex-start;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .comment-form button:hover {
            background-color: #0056b3;
        }

        h2{
            margin-bottom: 5px;
            font-family: "Jacques Francois Shadow", serif;
            font-size: 28px;
        }
        p{
            padding-left: 5px;
            font-family: "Ubuntu", sans-serif;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Blog Post Content -->
    <div class="blog-post">
        <a href="./readBlogs.php" class="back-link">← Back to Blogs</a>
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <p>by <?php echo htmlspecialchars($post['author']); ?> | <?php echo timeAgo($post['created_at']); ?></p>
        <img src="<?php echo htmlspecialchars($post['img']); ?>" alt="Blog Image">
        <h2>Description</h2>
        <div class="blog-post-content">
            <p><?php echo htmlspecialchars($post['description']); ?></p>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="comments-section">
        <h2>Comments</h2>

        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                    <span> - <?php echo timeAgo($comment['created_at']); ?></span>
                    <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No comments yet. Be the first to comment!</p>
        <?php endif; ?>

        <!-- Comment Form -->
        <div class="comment-form">
            <form action="../pages/add_comment.php" method="POST">
                <!-- Hidden input to pass the post_id -->
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <input type="text" name="username" placeholder="Your name" required>
                <textarea name="comment" placeholder="Write your comment here..." required rows="5"></textarea>
                <button type="submit">Submit Comment</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
