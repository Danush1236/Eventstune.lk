<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="artistlogin.css">
    <!-- Google icon font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <div class="background-image"></div>
    <div class="login-center">
        <div class="login-container">
            <div class="back-button">
                <span class="material-icons">chevron_left</span>
            </div>
            <h1>Welcome back!</h1>
            <p class="subtitle">Enter your Credentials to access your artist account</p>
            
            <?php
            session_start();
            if (isset($_SESSION['login_error'])) {
                echo '<div class="error-message" style="color:red; margin-bottom:10px;">' . htmlspecialchars($_SESSION['login_error']) . '</div>';
                unset($_SESSION['login_error']);
            }
            ?>
            <form class="login-form" action="../include/artistlogin.php" method="POST">
                <div class="form-group">
                    <input type="email" id="email" name="artist_email" placeholder="Email address" required>
                </div>
                <div class="form-group password-container">
                    <input type="password" id="password" name="artist_pwd" placeholder="Password" required>
                    <button type="button" class="toggle-password">
                        <span class="material-icons visibility-icon">visibility_off</span>
                    </button>
                </div>
                
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#" class="forgot-password">Forgotten password</a>
                </div>
                
                <button type="submit" class="login-btn">Login</button>
                
                <div class="divider">
                    <span>or</span>
                </div>
                
                <div class="social-login">
                    <button type="button" class="google-btn">
                        <img src="google-icon.png" alt="Google logo">
                        Sign in with Google
                    </button>
                    <button type="button" class="apple-btn">
                        <img src="apple-icon.png" alt="Apple logo">
                        Sign in with Apple
                    </button>
                </div>
                
                <div class="signup-link">
                    <p>Don't have an account? <a href="artistsignup.php">Sign up</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelector('.toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password'); // Accessing the password input by id
            const icon = this.querySelector('.visibility-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.textContent = 'visibility';
                icon.setAttribute('aria-label', 'Hide password');
            } else {
                passwordInput.type = 'password';
                icon.textContent = 'visibility_off';
                icon.setAttribute('aria-label', 'Show password');
            }
        });
        // Update back button to go to loginselection.php
        document.querySelector('.back-button').addEventListener('click', function() {
            window.location.href = 'loginselection.php';
        });
    </script>
</body>
</html>
