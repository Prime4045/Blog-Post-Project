<?php
// Include the database connection
include '../includes/config.php';

// Get the blog ID from the URL (using the GET method)
$blog_id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Ensure it's an integer

// Check if blog ID is valid
if ($blog_id > 0) {
    // Fetch the specific blog post using the blog ID
    $sql = "SELECT * FROM posts WHERE id = $blog_id";
    $result = mysqli_query($conn, $sql);

    // Check if the blog post exists
    if ($result && mysqli_num_rows($result) > 0) {
        $post = mysqli_fetch_assoc($result); // Fetch the post details
    } else {
        // If no blog found, redirect or show an error
        echo "Blog not found.";
        exit;
    }
} else {
    echo "Invalid blog ID.";
    exit;
}

// Handle blog deletion if the delete button is clicked
if (isset($_POST['delete_blog'])) {
    // Delete the blog from the database
    $delete_sql = "DELETE FROM posts WHERE id = $blog_id";
    if (mysqli_query($conn, $delete_sql)) {
        echo "Blog deleted successfully.";
        // Redirect back to the blog list or dashboard
        header("Location: ./crudBlogs.php");
        exit;
    } else {
        echo "Error deleting blog: " . mysqli_error($conn);
    }
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
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/blogs.css">
    <link rel="stylesheet" href="../css/user.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .blog-container {
            max-width: 70vw;
            margin: 20px 50px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-content: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .blog-title {
            color: #fff;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .edit-icon {
            cursor: pointer;
            margin-left: 10px;
            font-size: 18px;
            color: #007bff;
            display: none;
            /* Initially hidden */
        }

        .blog-meta {
            font-size: 14px;
            color: #888;
            margin-bottom: 20px;
        }

        .blog-image {
            width: 80%;
            aspect-ratio: 16 / 9;
            object-fit: cover;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .blog-content {
            font-size: 18px;
            line-height: 1.6;
            position: relative;
        }

        .edit-field {
            display: block;
            cursor: pointer;
            padding: 5px 2px;
        }

        .save-icon {
            display: none;
            cursor: pointer;
            margin-left: 10px;
            font-size: 18px;
            color: #28a745;
        }

        h2 {
            display: inline;
        }

        .blog-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .blog-buttons button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
        }

        .button:hover {
            background-color: #0056b3;
        }

        #delete-blog-btn{
            background-color: #dc3545;
        }

        #delete-blog-btn:hover {
            background-color: #c82333;
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
            <a href="#"><i class='bx bx-message'></i><span>Messages</span></a>
            <a href="#"><i class='bx bx-cog'></i><span>Settings</span></a>
            <a href="../pages/logout.php"><i class='bx bx-log-out'></i><span>Logout</span></a>
        </div>
    </div>

    <!-- Sidebar Toggle -->
    <button class="sidebar-toggle" onclick="toggleSidebar()"><i class='bx bx-menu-alt-left'></i></button>

    <!-- Main Content -->
    <main>
        <div class="blog-container">
            <!-- Blog title -->
            <div class="title-part">
                <h2>Title:</h2>
                <i class="bx bx-edit edit-icon" id="edit-title-icon" onclick="enableEditing('blog-title')"></i>
                <i class="bx bx-check save-icon" id="save-blog-title" onclick="saveChanges('blog-title')"></i>
                <h1 class="blog-title">
                    <span class="edit-field" id="blog-title"><?php echo htmlspecialchars($post['title']); ?></span>
                </h1>

                <!-- Blog metadata (author, category, and time ago) -->
                <div class="blog-meta">
                    <span>By: <?php echo htmlspecialchars($post['author']); ?></span> |
                    <span><?php echo strtoupper(htmlspecialchars($post['category'])); ?></span> |
                    <span><?php echo timeAgo($post['created_at']); ?></span>
                </div>
            </div>

            <!-- Blog image -->
            <img src="<?php echo htmlspecialchars($post['img']); ?>" alt="Blog Image" class="blog-image">

            <!-- Blog content -->
            <div class="blog-content">
                <h2>Description:</h2>
                <i class="bx bx-edit edit-icon" id="edit-description-icon" onclick="enableEditing('blog-description')"></i>
                <i class="bx bx-check save-icon" id="save-blog-description" onclick="saveChanges('blog-description')"></i>
                <p>
                    <span class="edit-field" id="blog-description"><?php echo htmlspecialchars($post['description']); ?></span>
                </p>
            </div>

            <!-- Update & Delete Blog Button -->
            <div class="blog-buttons">
                <!-- Update Blog Button -->
                <button id="update-blog-btn" onclick="showEditIcons()">Update Blog</button>

                <!-- Delete Blog Button -->
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="delete_blog" value="1">
                    <button id="delete-blog-btn" type="submit" onclick="return confirm('Are you sure you want to delete this blog?');">Delete Blog</button>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Function to show edit icons
        function showEditIcons() {
            document.getElementById('edit-title-icon').style.display = 'inline'; // Show edit icon for title
            document.getElementById('edit-description-icon').style.display = 'inline'; // Show edit icon for description
        }

        // Function to make the field editable
        function enableEditing(fieldId) {
            const field = document.getElementById(fieldId);
            field.contentEditable = "true";
            field.focus();

            // Show the save icon
            document.getElementById(`save-${fieldId}`).style.display = 'inline-block';
        }

        // Function to save changes via AJAX
        function saveChanges(fieldId) {
            const newValue = document.getElementById(fieldId).innerText;
            const blogId = <?php echo $blog_id; ?>; // Get the current blog ID

            // Send AJAX request to update the field
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "../pages/updateBlogField.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log("Field updated successfully");
                    // Hide the save icon
                    document.getElementById(`save-${fieldId}`).style.display = 'none';
                    document.getElementById(fieldId).contentEditable = "false";
                } else {
                    console.error("Error updating field");
                }
            };
            xhr.send(`field=${fieldId}&value=${encodeURIComponent(newValue)}&id=${blogId}`);
        }

        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');
        }
    </script>
</body>

</html>