<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../public/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | NGL Clone</title>
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
            background-color: var(--ig-light-gray);
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
            max-width: 400px;
            background-color: #fff;
            border-radius: 3px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Header */
        .header {
            padding: 30px 0;
            text-align: center;
            border-bottom: 1px solid var(--ig-border);
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            background: linear-gradient(45deg, var(--ig-purple), var(--ig-pink), var(--ig-orange));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .header h2 {
            font-size: 18px;
            font-weight: 600;
            color: var(--ig-dark-gray);
            margin-top: 10px;
        }

        /* Form Section */
        .form-container {
            padding: 25px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #8e8e8e;
        }

        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--ig-border);
            border-radius: 3px;
            font-size: 14px;
        }

        input[type="text"]:focus,
        input[type="date"]:focus {
            outline: none;
            border-color: var(--ig-purple);
        }

        .submit-btn {
            background: linear-gradient(45deg, var(--ig-purple), var(--ig-pink), var(--ig-orange));
            color: white;
            border: none;
            padding: 10px 0;
            border-radius: 4px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            margin-top: 10px;
            transition: opacity 0.2s;
        }

        .submit-btn:hover {
            opacity: 0.9;
        }

        /* Footer */
        .footer {
            padding: 20px;
            text-align: center;
            border-top: 1px solid var(--ig-border);
        }

        .footer p {
            color: #8e8e8e;
            font-size: 14px;
        }

        .footer a {
            color: var(--ig-purple);
            text-decoration: none;
            font-weight: 600;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .container {
                width: 95%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="logo">NGL Clone</div>
        <h2>Register</h2>
    </div>
    
    <div class="form-container">
        <form method="POST" action="../process/register.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" required>
            </div>
            
            <button type="submit" class="submit-btn">
                <i class="fas fa-user-plus"></i> Register
            </button>
        </form>
    </div>
    
    <div class="footer">
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

</body>
</html>