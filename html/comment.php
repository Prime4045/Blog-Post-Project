<?php
session_start();
include '../includes/config.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Access denied. Please log in to view your comments.");
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch the posts authored by the logged-in user
$posts_sql = "SELECT id, title FROM posts WHERE user_id = $user_id";
$posts_result = mysqli_query($conn, $posts_sql);

// Check for errors in the query
if (!$posts_result) {
    die('Error fetching posts: ' . mysqli_error($conn));
}

$posts = [];
while ($row = mysqli_fetch_assoc($posts_result)) {
    $posts[] = $row;
}

// If no posts are authored, show a message
if (empty($posts)) {
    die("You haven't authored any posts yet.");
}

// Fetch comments for the logged-in user's posts
$comments_sql = "
    SELECT 
        c.comment,
        c.username,
        c.created_at,
        p.id AS post_id,
        p.title AS post_title
    FROM comments c
    JOIN posts p ON c.post_id = p.id
    WHERE p.user_id = $user_id
    ORDER BY c.created_at DESC";
$comments_result = mysqli_query($conn, $comments_sql);

// Check for errors in the query
if (!$comments_result) {
    die('Error fetching comments: ' . mysqli_error($conn));
}

$comments = [];
while ($row = mysqli_fetch_assoc($comments_result)) {
    $comments[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Blog Comments</title>
    <link rel="stylesheet" href="../css/user.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Post Comments Section */
        .post-comments {
            margin: 20px 0;
            padding: 15px;
            color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .post-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ff6363;
            border-bottom: 2px solid #f2f2f2;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .comment {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .comment:last-child {
            border-bottom: none;
        }

        .comment-author {
            font-weight: bold;
            color: #cad2ff;
            margin-right: 10px;
        }

        .comment-date {
            font-size: 0.9rem;
            color: #888;
        }

        .comment-content {
            margin-top: 8px;
            font-size: 1rem;
            line-height: 1.5;
        }

        /* Empty Comments Message */
        .post-comments p {
            font-size: 1rem;
            color: #888;
            margin: 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="menu">
            <a href="./user.php"><i class='bx bx-home'></i><span>Dashboard</span></a>
            <a href="#"><i class='bx bx-user'></i><span>Profile</span></a>
            <a href="./crudBlogs.php"><i class='bx bx-book'></i><span>My Blogs</span></a>
            <a href="./comment.php"><i class='bx bx-message'></i><span>Comments</span></a>
            <a href="../pages/logout.php"><i class='bx bx-log-out'></i><span>Logout</span></a>
        </div>
    </div>

    <!-- Sidebar Toggle -->
    <button class="sidebar-toggle" onclick="toggleSidebar()"><i class='bx bx-menu-alt-left'></i></button>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <nav>
                <div class="nav-left">
                    <div class="page-title">Post Comments</div>
                </div>
                <div class="nav-right">
                    <ul>
                        <li class="profile-img"><a href=""><img src="../images/image2.jpg" alt=""></a></li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- Display comments -->
        <div class="post-container">
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post-comments">
                        <div class="post-title"><?php echo htmlspecialchars($post['title']); ?></div>

                        <?php
                        // Filter comments for this specific post
                        $post_comments = array_filter($comments, function ($comment) use ($post) {
                            return $comment['post_id'] == $post['id'];
                        });
                        ?>

                        <?php if (!empty($post_comments)): ?>
                            <?php foreach ($post_comments as $comment): ?>
                                <div class="comment">
                                    <div>
                                        <span class="comment-author"><?php echo htmlspecialchars($comment['username']); ?></span>
                                        <span class="comment-date"><?php echo htmlspecialchars($comment['created_at']); ?></span>
                                    </div>
                                    <div class="comment-content">
                                        <?php echo htmlspecialchars($comment['comment']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No comments for this post yet.</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You don't have any posts yet.</p>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');
        }
    </script>
</body>

</html>