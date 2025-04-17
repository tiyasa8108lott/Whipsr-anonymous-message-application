<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted username and date of birth
    $username = $_POST['username'];
    $dob = $_POST['dob'];

    // Prepare the SQL query to check if the user exists with matching username AND dob
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND dob = ?");
    $stmt->bind_param("ss", $username, $dob);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists with matching credentials
    if ($result->num_rows === 1) {
        // User found with matching username and DOB, create session variables
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['slug'] = $user['slug'];

        // Redirect to dashboard
        header("Location: ../public/dashboard.php");
        exit();
    } else {
        // Display error message for invalid credentials
        echo "<div style='text-align: center; margin-top: 50px; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif;'>";
        echo "<p style='color: #e74c3c; font-size: 16px; margin-bottom: 15px;'>Invalid username or date of birth.</p>";
        echo "<a href='../public/login.php' style='color: #833AB4; text-decoration: none; font-weight: 600;'>Go back to login</a>";
        echo "</div>";
    }
}
?>