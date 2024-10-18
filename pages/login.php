<?php
session_start();
include '../includes/config.php'; // DB connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Login successful - store session data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Get the current time
            $login_time = date('Y-m-d H:i:s');
            $log_entry = "User ID: " . $user['id'] . " | Email: " . $user['email'] . " | Login Time: " . $login_time . PHP_EOL;

            // Log the login time to logs.txt
            file_put_contents('../logs/logs.txt', $log_entry, FILE_APPEND);

            // Redirect to user dashboard
            header("Location: ../html/user.php");
            exit;
        } else {
            // Incorrect password
            header("Location: ../html/login.html?error=incorrect_password");
            exit;
        }
    } else {
        // User not found
        header("Location: ../html/login.html?error=user_not_registered");
        exit;
    }
}
