<?php
session_start();
include '../includes/config.php'; // Database connection

// Check if user is logged in by checking session
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
    header('Location: ../html/login.html');
    exit();
}

// Fetch user's blog posts from the database based on session user ID
$user_id = $_SESSION['user_id']; // Get the user ID from session
$sql = "SELECT * FROM posts WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);

// Check if any posts were found
if ($result && mysqli_num_rows($result) > 0) {
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC); // Fetch all posts by the user
} else {
    $posts = []; // No posts found
}

// Helper function to calculate time ago
function timeAgo($time)
{
    $time_difference = time() - strtotime($time);
    if ($time_difference < 1) {
        return 'Just now';
    }
    $condition = array(
        12 * 30 * 24 * 60 * 60 =>  'year',
        30 * 24 * 60 * 60       =>  'month',
        24 * 60 * 60            =>  'day',
        60 * 60                 =>  'hour',
        60                      =>  'minute',
        1                       =>  'second'
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
    <title>User's Blogs</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/user.css">
    <link rel="stylesheet" href="../css/blogs.css">
    <style>
        .sidebar {
            max-height: 155vh;
            min-height: 100vh;
        }

        .blog-card-content p {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            /* Limit to 3 lines */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            max-height: 4.5em;
            /* Adjust height based on font size */
        }

        .menu a:last-child {
            color: red;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
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
    <button title="toggleBtn" class="sidebar-toggle" onclick="toggleSidebar()"><i class='bx bx-menu-alt-left'></i></button>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <nav>
                <div class="nav-left">
                    <div class="page-title">My Blogs</div>
                    <!-- Search Bar -->
                    <div class="search-bar">
                        <input type="text" placeholder="Search...">
                        <i class='bx bx-search'></i>
                    </div>
                </div>
                <div class="nav-right">
                    <ul>
                        <li class="profile-img"><a href=""><img src="../images/image2.jpg" alt=""></a></li>
                    </ul>
                </div>
            </nav>
        </header>

        <section class="blog-section">
            <div class="blog-cards">

                <!-- Dynamically generate blog cards -->
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="blog-card">
                            <img src="<?php echo htmlspecialchars($post['img']); ?>" alt="Blog Image">
                            <div class="blog-card-content">
                                <span class="badge"><?php echo strtoupper(htmlspecialchars($post['category'])); ?></span>
                                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                                <p><?php echo htmlspecialchars($post['description']); ?></p>
                                <div class="other-details">
                                    <div class="blog-author">
                                        <img src="https://via.placeholder.com/40" alt="Author">
                                        <div class="author-details">
                                            <span class="author"><?php echo htmlspecialchars($post['author']); ?></span>
                                            <span><?php echo timeAgo($post['created_at']); ?></span>
                                        </div>
                                    </div>
                                    <a href="./viewBlog.php?id=<?php echo $post['id']; ?>" class="read-more-btn">Read More</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?> 
                <?php else: ?>
                    <p>No blog posts found.</p>
                <?php endif; ?>

            </div>
        </section>
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