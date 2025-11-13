<?php
session_start();

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['admin_login'])) {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['isAdmin'] = true;
            $_SESSION['adminUsername'] = $username;
            header('Location: admin-dashboard.php');
            exit();
        } else {
            $error = "Invalid username or password";
        }
    } elseif (isset($_POST['user_login'])) {
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['userType'] = 'user';
        header('Location: coffee-landing.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - The Malvar Bat Cave Cafe</title>
    <link rel="icon" type="image/png" href="./images/logoo.png">
    <link rel="stylesheet" href="coffee-landing.css?v=<?php echo time(); ?>">
    <style>
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('images/interior-malvar.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding: 20px;
            position: relative;
        }

        .login-page::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(44, 24, 16, 0.7);
            z-index: 0;
        }

        .login-container {
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            position: relative;
            z-index: 1;
        }

        .login-brand {
            background: linear-gradient(135deg, #2c1810 0%, #4a3728 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        .login-brand img {
            width: 120px;
            height: 120px;
            margin-bottom: 30px;
            filter: drop-shadow(0 10px 20px rgba(217, 119, 6, 0.3));
        }

        .login-brand h1 {
            font-size: 28px;
            margin-bottom: 15px;
            color: #d4b896;
        }

        .login-brand p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
        }

        .login-forms {
            padding: 60px 40px;
        }

        .login-forms h2 {
            font-size: 32px;
            color: #2c1810;
            margin-bottom: 10px;
        }

        .login-forms .subtitle {
            color: #5A3E36;
            margin-bottom: 40px;
            font-size: 14px;
        }

        .login-options {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .login-card {
            border: 2px solid #e5e7eb;
            border-radius: 15px;
            padding: 25px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .login-card:hover {
            border-color: #d4b896;
            background: #fff7ed;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(217, 119, 6, 0.2);
        }

        .login-card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .login-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #d4b896 0%, #c9964c 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .login-card h3 {
            font-size: 20px;
            color: #2c1810;
            margin: 0;
        }

        .login-card p {
            color: #5A3E36;
            font-size: 13px;
            margin: 0;
        }

        .admin-form {
            display: none;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #e5e7eb;
        }

        .admin-form.active {
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #2c1810;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #d4b896;
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.1);
        }

        .login-btn {
            width: 100%;
            background: linear-gradient(135deg, #2c1810 0%, #4a3728 100%);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .back-btn {
            background: #e5e7eb;
            color: #2c1810;
            margin-top: 10px;
        }

        .back-btn:hover {
            background: #d1d5db;
        }

        .error-message {
            background: #fee;
            color: #c00;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
            }

            .login-brand {
                padding: 40px 30px;
            }

            .login-brand img {
                width: 80px;
                height: 80px;
            }

            .login-brand h1 {
                font-size: 24px;
            }

            .login-forms {
                padding: 40px 30px;
            }

            .login-forms h2 {
                font-size: 26px;
            }
        }

        @media (max-width: 480px) {
            .login-page {
                padding: 10px;
            }

            .login-brand {
                padding: 30px 20px;
            }

            .login-forms {
                padding: 30px 20px;
            }

            .login-forms h2 {
                font-size: 22px;
            }

            .login-card {
                padding: 20px;
            }

            .login-icon {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }

            .login-card h3 {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <div class="login-page">
        <div class="login-container">
            <!-- Brand Section -->
            <div class="login-brand">
                <img src="images/logo.png" alt="The Malvar Bat Cave Cafe">
                <h1>The Malvar Bat Cave Cafe</h1>
                <p>The premier late-night study, social, and coffee spot near BatStateU Malvar Campus.</p>
            </div>

            <!-- Login Forms Section -->
            <div class="login-forms">
                <h2>Welcome Back</h2>
                <p class="subtitle">Please select how you want to login</p>

                <div class="login-options" id="loginOptions">
                    <!-- User Login Card -->
                    <form method="POST" style="margin: 0;">
                        <div class="login-card" onclick="this.parentElement.submit()">
                            <div class="login-card-header">
                                <div class="login-icon">👤</div>
                                <div>
                                    <h3>Continue as User</h3>
                                    <p>Browse menu and make reservations</p>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="user_login" value="1">
                    </form>

                    <!-- Admin Login Card -->
                    <div class="login-card" onclick="showAdminForm()">
                        <div class="login-card-header">
                            <div class="login-icon">🔐</div>
                            <div>
                                <h3>Admin Login</h3>
                                <p>Manage bookings and reservations</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Login Form -->
                <form class="admin-form" id="adminForm" method="POST">
                    <h3 style="color: #2c1810; margin-bottom: 20px;">Admin Login</h3>

                    <?php if (isset($error)): ?>
                        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required placeholder="Enter your username">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required placeholder="Enter your password">
                    </div>

                    <input type="hidden" name="admin_login" value="1">
                    <button type="submit" class="login-btn">Login as Admin</button>
                    <button type="button" class="login-btn back-btn" onclick="hideAdminForm()">Back</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showAdminForm() {
            document.getElementById('loginOptions').style.display = 'none';
            document.getElementById('adminForm').classList.add('active');
        }

        function hideAdminForm() {
            document.getElementById('loginOptions').style.display = 'flex';
            document.getElementById('adminForm').classList.remove('active');
        }

        <?php if (isset($error)): ?>
            // Show admin form if there was an error
            showAdminForm();
        <?php endif; ?>
    </script>
</body>

</html>