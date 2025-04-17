<?php
require_once '../config/db.php';

$user_id = intval($_POST['user_id']);
$message = trim($_POST['message']);

if (!empty($message)) {
    $stmt = $conn->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $message);
    $stmt->execute();
   
} else {
    echo "Please enter a message.";
}
?>
