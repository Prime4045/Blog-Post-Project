<?php
// Database connection
include '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $subject = htmlspecialchars(trim($_POST["subject"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    // SQL query to insert data into the messages table
    $sql = "INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        // Execute statement and check if successful
        if ($stmt->execute()) {
            echo "<script>alert('Your message has been submitted successfully!'); window.location.href='../html/contact.html';</script>";
        } else {
            echo "<script>alert('Sorry, something went wrong. Please try again.'); window.location.href='../html/contact.html';</script>";
        }

        // Close statement
        $stmt->close();
    } else {
        echo "<script>alert('Database error.'); window.location.href='../html/contact.html';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='../html/contact.html';</script>";
}

// Close connection
$conn->close();

