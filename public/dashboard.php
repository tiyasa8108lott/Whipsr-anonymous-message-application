<?php
// Start session to check for login status
session_start();

// Include database connection
require_once '../config/db.php';

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$query = "SELECT username, slug FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch anonymous messages for the logged-in user
$query_messages = "SELECT id, message, timestamp FROM messages WHERE user_id = ? ORDER BY timestamp DESC";
$stmt_messages = $conn->prepare($query_messages);
$stmt_messages->bind_param('i', $user_id);
$stmt_messages->execute();
$messages = $stmt_messages->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome, <?= isset($user['username']) ? htmlspecialchars($user['username']) : 'User' ?> | Whispr</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Instagram-inspired colors and styles */
        :root {
            --ig-purple: #833AB4;
            --ig-pink: #C13584;
            --ig-orange: #F77737;
            --ig-yellow: #FCAF45;
            --ig-light-gray: #FAFAFA;
            --ig-medium-gray: #EFEFEF;
            --ig-dark-gray: #262626;
            --ig-border: #DBDBDB;
        }

        /* Basic Layout */
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--ig-light-gray);
            color: var(--ig-dark-gray);
            padding: 0;
            margin: 0;
        }

        .container {
            max-width: 750px; /* Increased width */
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            margin-bottom: 30px;
            padding: 20px; /* Increased padding */
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(45deg, var(--ig-purple), var(--ig-pink), var(--ig-orange));
            padding: 15px 25px;
            color: white;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-logo {
            font-size: 24px; /* Increased font size */
            font-weight: bold;
            letter-spacing: 1px;
        }

        .logout-btn {
            color: #fff;
            text-decoration: none;
            background-color: rgba(255, 255, 255, 0.2);
            padding: 10px 18px;
            border-radius: 4px;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        /* Welcome Section */
        .welcome-section {
            padding: 20px;
            border-bottom: 1px solid var(--ig-border);
        }

        h2 {
            font-size: 24px; /* Increased font size */
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--ig-dark-gray);
        }

        /* Link Section */
        .link-section {
            padding: 30px; /* Increased padding */
            background-color: var(--ig-light-gray);
            border-radius: 10px;
            margin: 0 0 30px;
            display: flex;
            flex-direction: column; /* Stack content vertically */
            align-items: center;
        }

        .link {
            color: var(--ig-purple);
            text-decoration: none;
            font-weight: 600;
            word-break: break-all;
            margin-bottom: 15px; /* Space between the link and the button */
        }

        .copy-link-btn {
            background: linear-gradient(45deg, var(--ig-purple), var(--ig-pink));
            color: white;
            padding: 12px 20px; /* Increased padding */
            border: none;
            border-radius: 35px; /* Larger border radius */
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .copy-link-btn:hover {
            background: linear-gradient(45deg, var(--ig-orange), var(--ig-yellow));
            transform: scale(1.05);
        }

        /* Messages Section */
        .messages-header {
            padding: 0 20px;
            margin-bottom: 20px;
        }

        h3 {
            font-size: 20px; /* Increased font size */
            font-weight: 600;
            color: var(--ig-dark-gray);
        }

        .messages-container {
            padding: 0 20px 20px;
        }

        .message-card {
            background-color: #fff;
            border: 1px solid var(--ig-border);
            border-radius: 10px;
            padding: 20px; /* Increased padding */
            margin-bottom: 20px; /* Increased margin between messages */
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
        }

        .message-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .message-card::after {
            content: '\f054';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--ig-purple);
            opacity: 0;
            transition: opacity 0.2s;
        }

        .message-card:hover::after {
            opacity: 1;
        }

        .message-content {
            font-size: 18px; /* Increased font size */
            color: var(--ig-dark-gray);
            margin-bottom: 15px;
            /* Limit text to 2 lines with ellipsis for overflow */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .message-time {
            font-size: 14px;
            color: #8e8e8e;
            display: block;
            margin-bottom: 15px;
        }

        .share-btn {
            display: inline-block;
            padding: 15px 25px;
            color: #fff;
            font-size: 16px;
            text-align: center;
            border-radius: 35px;
            font-weight: 600;
            margin: 15px;
            transition: background-color 0.3s;
            cursor: pointer;
        }

        .whatsapp-btn {
            background-color: #25D366;
        }

        .whatsapp-btn:hover {
            background-color: #128C7E;
        }

        .instagram-btn {
            background-color: #E4405F;
        }

        .instagram-btn:hover {
            background-color: #C13584;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #8e8e8e;
            background-color: var(--ig-light-gray);
            border-radius: 10px;
        }

        /* Responsive adjustments */
        @media (max-width: 640px) {
            .container {
                margin: 0;
                border-radius: 0;
                box-shadow: none;
                max-width: 100%;
            }

            .copy-link-btn {
                font-size: 14px; /* Smaller font size on small screens */
                padding: 10px 15px; /* Smaller padding */
            }

            .share-btn {
                padding: 12px 20px; /* Adjusted padding for smaller screens */
            }

            .message-card {
                padding: 15px; /* Reduced padding for smaller screens */
            }
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="nav-logo">Whispr</div>
    <a href="../process/logout.php" class="logout-btn">Logout</a>
</div>

<div class="container">
    <div class="welcome-section">
        <h2>Welcome, <?= isset($user['username']) ? htmlspecialchars($user['username']) : 'User' ?></h2>
    </div>
    
    <div class="link-section">
        <p>Your link:</p>
        <a href="http://localhost:2020/ngl-clone/public/u/<?= isset($user['slug']) ? $user['slug'] : '' ?>" class="link"><?= "http://localhost:2020/ngl-clone/public/u/{$user['slug']}" ?></a>
        <button class="copy-link-btn" onclick="copyLink()">
            <i class="fas fa-copy"></i> Copy Link
        </button>
    </div>

    <div class="messages-header">
        <h3>Share Your Link on Social Media</h3>
    </div>

    <div class="messages-container">
        <div class="share-buttons">
            <button class="share-btn whatsapp-btn" onclick="shareMessage('http://localhost:2020/ngl-clone/public/u/<?= $user['slug'] ?>', 'whatsapp')">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </button>
            <button class="share-btn instagram-btn" onclick="shareMessage('http://localhost:2020/ngl-clone/public/u/<?= $user['slug'] ?>', 'instagram')">
                <i class="fab fa-instagram"></i> Instagram
            </button>
        </div>
    </div>

    <div class="messages-header">
        <h3>Anonymous Messages</h3>
    </div>
    
    <div class="messages-container">
        <?php if ($messages && $messages->num_rows > 0): ?>
            <?php while ($row = $messages->fetch_assoc()): ?>
                <div class="message-card" onclick="window.location.href='message.php?id=<?= $row['id'] ?>'">
                    <p class="message-content"><?= htmlspecialchars($row['message']) ?></p>
                    <span class="message-time"><?= $row['timestamp'] ?></span>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <p>No messages yet. Share your link to get anonymous messages!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Function to copy the user's NGL link
    function copyLink() {
        const link = document.querySelector('.link').textContent;
        navigator.clipboard.writeText(link).then(function() {
            alert('Link copied to clipboard!');
        });
    }

    // Function to share the anonymous message
    function shareMessage(message, platform) {
        const url = encodeURIComponent(message);
        
        if (platform === 'whatsapp') {
            const whatsappUrl = `https://wa.me/?text=${url}`;
            window.open(whatsappUrl, '_blank');
        } else if (platform === 'instagram') {
            alert("Message copied! Open Instagram and paste in your story or DM.");
            navigator.clipboard.writeText(message);
            setTimeout(() => window.open('https://www.instagram.com', '_blank'), 1500);
        }
    }
</script>

</body>
</html>