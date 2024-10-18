<?php
// Include the database connection
include '../includes/config.php'; // DB connection

// Initialize variables for messages
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch the email and new password from the form
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);

    // Check if the email exists in the database
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // If email exists, hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the user's password in the database
        $sql_update = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";

        if (mysqli_query($conn, $sql_update)) {
            // Password update success
            $message = 'Password updated successfully!';
        } else {
            // Error updating password
            $message = 'Error updating password: ' . mysqli_error($conn);
        }
    } else {
        // If email is not found in the database
        $message = 'Email not found';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../css/login.css">
    <style>
        body{
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container{
            height: 55%;
        }

        .container h2{
            margin-bottom: 20px;
        }

        form{
            gap: 5px;
        }

        .backToLogin{
            margin-top: 10px;
        }

        .message{
            margin-bottom: 15px;
            color: #008000;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Reset Password</h2>

        <!-- Display the success or error message -->
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Form for resetting the password -->
        <form action="./forget_pass.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" placeholder="Enter new password" required>

            <button type="submit">Reset Password</button>
        </form>

        <div class="backToLogin">
            <a href="../html/login.html">Back to Login</a>
        </div>
    </div>
</body>

</html>