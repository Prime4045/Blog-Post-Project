<?php
session_start();
include '../includes/config.php'; // Database connection

// Check if user is logged in by checking session
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page
    header('Location: ../html/login.html');
    exit();
}

// Fetch user data from the database based on session user ID
$user_id = $_SESSION['user_id']; // Get the user ID from session
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result); // Fetch the user data
} else {
    echo 'User not found';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/user.css">
    <style>
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
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
    <button class="sidebar-toggle" onclick="toggleSidebar()"><i class='bx bx-menu-alt-left'></i></button>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <nav>
                <div class="nav-left">
                    <div class="page-title">Dashboard</div>
                </div>
                <div class="nav-right">
                    <ul>
                        <li class="profile-img"><a href=""><img src="../images/image2.jpg" alt=""></a></li>
                    </ul>
                </div>

            </nav>
        </header>

        <main>
            <div class="card">
                <h2>My Profile</h2>
                <p>Username: <?php echo $user["username"]; ?></p>
                <p>Email: <?php echo $user["email"]; ?></p>
                <p>Member Since: <?php echo date("Y", strtotime($user["created_at"])); ?></p>
            </div>

            <!-- Create Blog Post Form -->
            <div class="form-container">
                <h2>Create Blog Post</h2>

                <?php
                // Check if the 'message' parameter is set in the URL
                if (isset($_GET['message'])) {
                    echo '<div class="success-message" id="successMessage">' . htmlspecialchars($_GET['message']) . '</div>';
                }
                ?>

                <form action="../pages/post.php" method="POST" class="formCreation" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="title">Blog Title<span>*</span></label>
                        <input type="text" id="title" name="title" placeholder="Enter the blog title" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Blog Description<span>*</span></label>
                        <textarea id="description" name="description" placeholder="Write a short description..." rows="4" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="img">Upload Blog Image <span>*</span></label>
                        <input type="file" id="img" name="img" accept="image/*" required>
                    </div>

                    <div class="form-group">
                        <label for="category">Blog Category<span>*</span></label>
                        <select id="category" name="category" required>
                            <option value="" disabled selected>Select a category</option>
                            <option value="technology">Technology</option>
                            <option value="food">Food</option>
                            <option value="automobile">Automobile</option>
                            <option value="health">Health</option>
                            <option value="travel">Travel</option>
                        </select>
                    </div>

                    <!-- Hidden field for the author name -->
                    <input type="hidden" name="author" value="<?php echo htmlspecialchars($user['username']); ?>">

                    <button type="submit" class="submit-btn">Submit Blog</button>
                </form>
            </div>
        </main>
    </div>
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');
        }

        // Wait for the DOM to load
        document.addEventListener("DOMContentLoaded", function() {
            // Get the success message element
            var successMessage = document.getElementById("successMessage");

            // If the element exists, set a timeout to hide it after 5 seconds (5000 milliseconds)
            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.display = "none"; // Hide the message
                }, 2000); // 5000 milliseconds = 5 seconds
            }
        });
    </script>
</body>

</html>