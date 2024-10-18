<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'users_blogs';

// Create a connection to MySQL server (without selecting a database initially)
$conn = mysqli_connect($host, $user, $password);

// Check connection to the server
if (!$conn) {
    die('Connection to MySQL server failed: ' . mysqli_connect_error());
}

// Check if the database exists
$db_check_query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$database'";
$db_check_result = mysqli_query($conn, $db_check_query);

// If the database does not exist, create it
if (!$db_check_result || mysqli_num_rows($db_check_result) == 0) {
    $create_db_query = "CREATE DATABASE $database";
    if (!mysqli_query($conn, $create_db_query)) {
        error_log('Error creating database: ' . mysqli_error($conn));
        die('Error creating the database.');
    }
}

// Now, connect to the newly created or existing database
$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    error_log('Connection to the new database failed: ' . mysqli_connect_error());
    die('Error connecting to the database.');
}

// Check if 'users' table exists, and create it if not
$table_check_query = "SHOW TABLES LIKE 'users'";
$table_check_result = mysqli_query($conn, $table_check_query);

if (!$table_check_result || mysqli_num_rows($table_check_result) == 0) {
    $create_users_table = "
    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    if (!mysqli_query($conn, $create_users_table)) {
        error_log('Error creating users table: ' . mysqli_error($conn));
        die('Error creating users table.');
    }
}

// Check if 'posts' table exists, and create it if not
$table_check_query = "SHOW TABLES LIKE 'posts'";
$table_check_result = mysqli_query($conn, $table_check_query);

if (!$table_check_result || mysqli_num_rows($table_check_result) == 0) {
    $create_posts_table = "
    CREATE TABLE posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        img VARCHAR(255) NOT NULL,
        author VARCHAR(100) NOT NULL,
        category VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    if (!mysqli_query($conn, $create_posts_table)) {
        error_log('Error creating posts table: ' . mysqli_error($conn));
        die('Error creating posts table.');
    }
}

// Do NOT close the connection here, as it will be needed elsewhere in your application
// mysqli_close($conn);
