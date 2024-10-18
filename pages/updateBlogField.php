<?php
include '../includes/config.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $blog_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $field = isset($_POST['field']) ? $_POST['field'] : '';
    $value = isset($_POST['value']) ? $_POST['value'] : '';

    if ($blog_id > 0 && !empty($field) && !empty($value)) {
        // Sanitize input
        $field = mysqli_real_escape_string($conn, $field);
        $value = mysqli_real_escape_string($conn, $value);

        // Map the field name to database columns
        $allowed_fields = ['blog-title' => 'title', 'blog-description' => 'description'];
        if (array_key_exists($field, $allowed_fields)) {
            $db_column = $allowed_fields[$field];

            // Update the corresponding field in the database
            $sql = "UPDATE posts SET $db_column = '$value' WHERE id = $blog_id";
            if (mysqli_query($conn, $sql)) {
                echo "Field updated successfully.";
            } else {
                echo "Error updating field: " . mysqli_error($conn);
            }
        } else {
            echo "Invalid field.";
        }
    } else {
        echo "Invalid input.";
    }
}
