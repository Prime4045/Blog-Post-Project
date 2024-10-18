<?php
// Start the session to access session variables
session_start();

// Include your database connection file
include('../includes/config.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Collect form data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    // Get the logged-in user's ID from the session
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Ensure user is logged in
    } else {
        echo "User not logged in.";
        exit;
    }

    // Handle image upload
    if (isset($_FILES['img'])) {
        $img = $_FILES['img'];
        $img_name = $img['name'];
        $img_tmp_name = $img['tmp_name'];
        $img_error = $img['error'];

        // Check if there was no error during upload
        if ($img_error === 0) {
            // Generate unique image name to avoid conflicts
            $img_destination = '../uploads/' . uniqid('', true) . '-' . $img_name;

            // Move the uploaded file to the uploads folder
            if (move_uploaded_file($img_tmp_name, $img_destination)) {
                // Insert form data into the database, including user_id
                $sql = "INSERT INTO posts (user_id, title, description, img, author, category) 
                        VALUES ('$user_id', '$title', '$description', '$img_destination', '$author', '$category')";

                if (mysqli_query($conn, $sql)) {
                    // Redirect to user.php with a success message in the URL
                    header('Location: ../html/user.php?message=Blog+created+successfully');
                    exit; // Stop script execution after redirection
                } else {
                    echo 'Error: ' . mysqli_error($conn);
                }
            } else {
                echo "Error moving the uploaded image.";
            }
        } else {
            echo "Error uploading image. Error code: " . $img_error;
        }
    } else {
        echo "No image file uploaded.";
    }
}

// Close the database connection
mysqli_close($conn);
?>
