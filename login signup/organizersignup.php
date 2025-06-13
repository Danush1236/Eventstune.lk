<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="stylesheet" href="organizersignup.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <div class="background-image"></div>
    <div class="login-center">
        <div class="login-container">
            <div class="back-button">
                <span class="material-icons">chevron_left</span>
            </div>
            <h1>Organizer Registration</h1>
            <p class="subtitle">Create your account by providing the details below.</p>

            <?php
            session_start();
            if (isset($_SESSION['signup_error'])): ?>
                <div class="error-message" style="color: #d32f2f; margin-bottom: 15px; text-align:center;">
                    <?php
                    echo $_SESSION['signup_error'];
                    unset($_SESSION['signup_error']);
                    ?>
                </div>
            <?php elseif (isset($_SESSION['signup_success'])): ?>
                <div class="success-message" style="color: #388e3c; margin-bottom: 15px; text-align:center;">
                    <?php
                    echo $_SESSION['signup_success'];
                    unset($_SESSION['signup_success']);
                    ?>
                </div>
            <?php endif; ?>

            <form class="login-form" method="POST" action="../include/organizersign.php" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="text" name="organizer_name" placeholder="Name" required>
                </div>

                <div class="form-group">
                    <input type="text" name="organizer_company" placeholder="Company Name (Optional)">
                </div>

                <div class="form-group">
                    <input type="tel" name="organizer_phone" placeholder="Mobile Number" required>
                </div>

                <div class="form-group">
                    <input type="email" name="organizer_email" placeholder="Email address" required>
                </div>

                <div class="form-group password-container">
                    <input type="password" name="organizer_pwd" placeholder="Password" required>
                    <button type="button" class="toggle-password">
                        <span class="material-icons visibility-icon">visibility_off</span>
                    </button>
                </div>

                <div class="form-group password-container">
                    <input type="password" name="organizer_re_pwd" placeholder="Confirm Password" required>
                    <button type="button" class="toggle-password">
                        <span class="material-icons visibility-icon">visibility_off</span>
                    </button>
                </div>

                <div class="form-group" style="display: flex; align-items: center; font-size: 14px;">
                    <input type="checkbox" id="agree" name="agree" style="margin-right: 10px;">
                    <label for="agree">I agree to the <a href="#">terms and conditions</a></label>
                </div>

                <button type="submit" class="login-btn" name="submit">Sign up</button>

                <div class="divider"><span>or</span></div>

                <div class="social-login">
                    <button type="button" class="google-btn">
                        <img src="google-icon.png" alt="Google logo">
                        Sign up with Google
                    </button>
                    <button type="button" class="apple-btn">
                        <img src="apple-icon.png" alt="Apple logo">
                        Sign up with Apple
                    </button>
                </div>

                <div class="signup-link">
                    <p>Have an account? <a href="organizerlogin.php">Login</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        const toggleButtons = document.querySelectorAll('.toggle-password');
        toggleButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const input = this.previousElementSibling;
                const icon = this.querySelector('.visibility-icon');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.textContent = 'visibility';
                } else {
                    input.type = 'password';
                    icon.textContent = 'visibility_off';
                }
            });
        });
    </script>
    <script>
        window.onload = function() {
            window.scrollTo(0, 0);
        };
    </script>
    <script>
        // Back button functionality
        document.querySelector('.back-button').addEventListener('click', function() {
            window.location.href = 'signupselection.php';
        });
    </script>
    <script>
        // Remove query string from URL after page load (so error/success messages only show once)
        if (window.location.search.length > 0) {
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
</body>
</html>
