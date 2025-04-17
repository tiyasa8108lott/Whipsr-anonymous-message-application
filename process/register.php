<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = $_POST['username'];
    $dob = $_POST['dob'];

    // First check if username already exists
    $check_stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Username already exists
        echo "<div style='text-align: center; margin-top: 50px; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif;'>";
        echo "<p style='color: #e74c3c; font-size: 16px; margin-bottom: 15px;'>Username already exists. Please choose a different username.</p>";
        echo "<a href='../public/register.php' style='color: #833AB4; text-decoration: none; font-weight: 600;'>Go back to registration</a>";
        echo "</div>";
    } else {
        // Username is available, proceed with registration
        
        // Generate a unique slug based on the username
        $slug = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $username)) . rand(100, 999);

        // Prepare the SQL query to insert the user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, dob, slug) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $dob, $slug);

        // Execute the query and check if the user was successfully added
        if ($stmt->execute()) {
            // Set a session variable to show a success message on the login page
            session_start();
            $_SESSION['registration_success'] = true;
            
            // Redirect to login page
            header("Location: ../public/login.php");
            exit();
        } else {
            echo "<div style='text-align: center; margin-top: 50px; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif;'>";
            echo "<p style='color: #e74c3c; font-size: 16px; margin-bottom: 15px;'>Error: " . $stmt->error . "</p>";
            echo "<a href='../public/register.php' style='color: #833AB4; text-decoration: none; font-weight: 600;'>Try again</a>";
            echo "</div>";
        }
    }
}
?>