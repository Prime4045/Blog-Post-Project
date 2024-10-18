<?php
// Start session if needed (although not necessary for viewing)
session_start();
include '../includes/config.php'; // Include the database connection

// Initialize search query
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Fetch all blog posts from the database (or filter by search term)
$sql = "SELECT * FROM posts";
if (!empty($search)) {
    $sql .= " WHERE title LIKE '%$search%'";
}
$result = mysqli_query($conn, $sql);

// Check if any posts were found
if ($result && mysqli_num_rows($result) > 0) {
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC); // Fetch all posts
} else {
    $posts = []; // No posts found
}

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
    <title>Read Blogs</title>
    <link rel="stylesheet" href="../css/blogs.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Global styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Container styles */
        .container {
            max-width: 1200px;
            /* Set a max width for the main content */
            margin: 0 auto;
            /* Center the container */
            padding: 20px;
            /* Add padding */
        }

        /* Search bar styles */
        .search-bar {
            display: flex;
            justify-content: center;
            /* Center the search bar */
            margin-bottom: 20px;
            /* Space below the search bar */
        }

        .search-bar input[type="text"] {
            padding: 10px;
            width: 300px;
            /* Set a fixed width for the input */
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-bar button {
            padding: 10px 15px;
            margin-left: 10px;
            /* Space between input and button */
            background-color: #007BFF;
            /* Primary button color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            /* Smooth transition */
        }

        .search-bar button:hover {
            background-color: #0056b3;
            /* Darker shade on hover */
        }

        /* Blog section styles */
        .blog-section {
            margin-top: 20px;
            /* Space above the blog section */
        }

        .blog-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            /* Responsive grid */
            gap: 20px;
            /* Space between cards */
        }

        /* Blog card styles */
        .blog-card {
            background-color: white;
            /* White background for cards */
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s;
            /* Smooth transform on hover */
        }

        .blog-card:hover {
            transform: translateY(-5px);
            /* Slight lift on hover */
        }

        .blog-card h3 {
            margin: 0 0 10px;
            font-size: 18px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
        }

        .blog-card p {
            font-size: 14px;
            line-height: 1.5;
            height: 60px;
            /* Fixed height for descriptions */
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            /* Limit description to 3 lines */
            -webkit-box-orient: vertical;
            margin-bottom: 10px;
            /* Space below the description */
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">Blog Post</div>
        <nav>
            <ul>
                <li><a href="./index.html">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="./readBlogs.php">Blogs</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
        <div class="auth">
            <a href="./login.html" class="btn">Login</a>
            <a href="./register.html" class="btn secondary">Register</a>
        </div>
    </header>
    <section class="blog-section">
        <div class="container">

            <!-- Search bar -->
            <form method="GET" action="./readBlogs.php" class="search-bar">
                <input type="text" name="search" placeholder="Search blogs by title..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Search</button>
            </form>

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
                                    <a href="#" class="read-more-btn">Read More</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No blog posts found.</p>
                <?php endif; ?>

            </div>
        </div>
    </section>

</body>

</html>