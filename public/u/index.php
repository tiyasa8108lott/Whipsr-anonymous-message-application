<?php
require_once '../../config/db.php';

// Get the user slug from the URL
$slug = basename($_SERVER['REQUEST_URI']);

// Prepare and execute a query to fetch user based on slug
$stmt = $conn->prepare("SELECT * FROM users WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows == 0) {
    echo "Invalid user link.";
    exit();
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message to <?= htmlspecialchars($user['username']) ?> | NGL Clone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
            background: linear-gradient(135deg, var(--ig-purple) 0%, var(--ig-pink) 50%, var(--ig-orange) 100%);
            color: var(--ig-dark-gray);
            padding: 0;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 90%;
            max-width: 500px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        /* Header */
        .header {
            background: linear-gradient(45deg, var(--ig-purple), var(--ig-pink), var(--ig-orange));
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h2 {
            font-size: 20px;
            font-weight: 600;
            margin: 0;
        }

        /* Form Section */
        .form-container {
            padding: 25px;
        }

        .username-display {
            text-align: center;
            margin-bottom: 20px;
            color: var(--ig-dark-gray);
            font-size: 18px;
        }

        .username-display span {
            font-weight: 600;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        textarea {
            width: 100%;
            min-height: 150px;
            padding: 15px;
            border: 1px solid var(--ig-border);
            border-radius: 5px;
            margin-bottom: 15px;
            font-family: inherit;
            font-size: 16px;
            resize: vertical;
        }

        textarea:focus {
            outline: none;
            border-color: var(--ig-purple);
        }

        .submit-btn {
            background: linear-gradient(45deg, var(--ig-purple), var(--ig-pink), var(--ig-orange));
            color: white;
            border: none;
            padding: 12px 0;
            border-radius: 5px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .submit-btn:hover {
            opacity: 0.9;
        }

        /* Footer */
        .footer {
            padding: 15px;
            text-align: center;
            border-top: 1px solid var(--ig-border);
            color: #8e8e8e;
            font-size: 12px;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .container {
                width: 95%;
                border-radius: 8px;
            }
            
            .header {
                padding: 15px;
            }
            
            .form-container {
                padding: 20px;
            }
        }

        /* Message Status Styling */
        #messageStatus {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            color: #28a745;
        }

        .create-link {
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
        }

        .create-link a {
            color: var(--ig-purple);
            font-weight: 600;
            text-decoration: none;
        }

        .create-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Send Anonymous Message</h2>
    </div>
    
    <div class="form-container">
        <div class="username-display">
            Send a message to <span><?= htmlspecialchars($user['username']) ?></span>
        </div>
        
        <form id="messageForm">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <textarea id="message" name="message" placeholder="Type your anonymous message here..." required maxlength="500"></textarea>
            <button type="submit" class="submit-btn">
                <i class="fas fa-paper-plane"></i> Send Message
            </button>
        </form>
        
        <div id="messageStatus" style="display: none;"></div>
        
        <!-- Create Your Own Link Option -->
      <!-- Create Your Own Link Option -->
<!-- Create Your Own Link Option -->
<div class="create-link">
    <p>Don't have your own link? <a href="/ngl-clone/public/register.php">Create Your Own</a></p>
</div>


    </div>
    
    <div class="footer">
        <p>Messages are anonymous. Be kind and respectful.</p>
    </div>
</div>

<script>
    document.getElementById('messageForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        let formData = new FormData(this);
        
        // Make an AJAX request to send the message
        fetch('../../process/send_message.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // Show success message after the message is sent
            document.getElementById('messageStatus').style.display = 'block';
            document.getElementById('messageStatus').textContent = 'Message sent anonymously!';
            document.getElementById('message').value = ''; // Clear the textarea
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>

</body>
</html>
