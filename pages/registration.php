<?php
session_start();
include '../includes/config.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check for existing username and email
    $checkUsername = "SELECT * FROM users WHERE username = '$username'";
    $usernameResult = mysqli_query($conn, $checkUsername);
    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $emailResult = mysqli_query($conn, $checkEmail);

    if (mysqli_num_rows($usernameResult) > 0) {
        header('Location: ../html/register.html?error=username_taken');
        exit();
    } elseif (mysqli_num_rows($emailResult) > 0) {
        header('Location: ../html/register.html?error=email_taken');
        exit();
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if (mysqli_query($conn, $sql)) {
            header('Location: ../html/register.html?success=true');
            exit();
        } else {
            echo 'Error: ' . mysqli_error($conn);
        }
    }
}

