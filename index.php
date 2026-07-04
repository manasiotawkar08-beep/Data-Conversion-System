<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Converter - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
            border-radius: 16px;
            text-align: center;
        }

        /* Logo / Icon */
        .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin: 0 auto 20px;
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
            transition: transform 0.3s ease;
        }

        .logo-icon:hover {
            transform: scale(1.05);
        }

        /* Title */
        .login-title {
            font-size: 28px;
            font-weight: 600;
            color: #202124;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            font-size: 15px;
            color: #5f6368;
            margin-bottom: 30px;
            font-weight: 400;
        }

        /* Form */
        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #202124;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #dadce0;
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background: #f8f9fa;
            color: #202124;
            outline: none;
        }

        .form-group input:focus {
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        }

        .form-group input::placeholder {
            color: #9aa0a6;
            font-weight: 400;
        }

        /* Error Message */
        .error-message {
            background: #fce8e6;
            color: #d93025;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-align: left;
            animation: shake 0.4s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-6px); }
            75% { transform: translateX(6px); }
        }

        /* Login Button */
        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
            margin-top: 6px;
            letter-spacing: 0.3px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.35);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        /* Footer Links */
        .footer-links {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .footer-links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .footer-links a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .footer-links .demo-text {
            color: #5f6368;
            font-size: 13px;
        }

        .footer-links .demo-text code {
            background: #f1f3f4;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-family: 'Courier New', monospace;
            color: #202124;
            font-weight: 600;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 22px 0;
            color: #9aa0a6;
            font-size: 13px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #dadce0;
        }

        .divider::before {
            margin-right: 15px;
        }

        .divider::after {
            margin-left: 15px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }

            .login-title {
                font-size: 24px;
            }

            .logo-icon {
                width: 65px;
                height: 65px;
                font-size: 30px;
            }

            .footer-links {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Logo -->
        <div class="logo-icon">📊</div>

        <!-- Title -->
        <div class="login-title">Data Converter</div>
        <div class="login-subtitle">Sign in to continue</div>

        <!-- Error Message -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <span>⚠️</span> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="api/login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="Enter your username"
                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Enter your password"
                    required
                >
            </div>

            <button type="submit" class="login-btn">
                Sign In
            </button>
        </form>

        <div class="divider">or</div>

        <!-- Footer -->
        <div class="footer-links">
            <a href="#">Create account</a>
            <span class="demo-text">
                Demo: <code>admin</code> / <code>admin123</code>
            </span>
            <a href="#">Need help?</a>
        </div>
    </div>

    <script>
        // Auto-focus on username field
        document.getElementById('username').focus();

        // Simple form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!username || !password) {
                e.preventDefault();
                alert('⚠️ Please fill in all fields');
            }
        });
    </script>
</body>
</html>