<?php
session_start(); // Start the session

// Destroy all session data
session_destroy();

// Redirect to the home or login page (change the URL if needed)
header('Location: ../html/index.html'); // Assuming index.php is your homepage
exit();

