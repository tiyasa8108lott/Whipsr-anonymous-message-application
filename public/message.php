<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$message_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$query = "SELECT m.*, u.username FROM messages m 
          JOIN users u ON m.user_id = u.id 
          WHERE m.id = ? AND m.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $message_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: dashboard.php');
    exit();
}

$message = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Anonymous Message</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --ig-purple: #833AB4;
            --ig-pink: #C13584;
            --ig-orange: #F77737;
            --ig-dark: #262626;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--ig-purple), var(--ig-pink), var(--ig-orange));
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--ig-dark);
        }

        .message-container {
    background: #fff;
    padding: 30px;
    border-radius: 15px;
    max-width: 400px;         /* Reduced width */
    width: 90%;
    min-height: 300px;        /* Increased height */
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
}


        .message-container h2 {
            margin-bottom: 20px;
            font-size: 26px;
            color: var(--ig-pink);
        }

        .message-content {
            font-size: 18px;
            line-height: 1.6;
            color: #333;
            word-wrap: break-word;
        }

        .timestamp {
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>

<div class="message-container">
    <h2>Anonymous Message</h2>
    <div class="message-content">
        <?= nl2br(htmlspecialchars($message['message'])) ?>
    </div>
    <div class="timestamp">
        <?= $message['timestamp'] ?>
    </div>
</div>

</body>
</html>
