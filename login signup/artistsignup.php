<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Registration</title>
    <link rel="stylesheet" href="artistsignup.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <div class="background-image"></div>
    <div class="login-center">
        <div class="login-container">
            <div class="back-button">
                <span class="material-icons">chevron_left</span>
            </div>
            <h1>Artist Registration</h1>
            <p class="subtitle">Create your artist account by providing the details below.</p>

            <form class="login-form" action="../include/artistsign.php" method="POST">
                <div class="form-group">
                    <select name="artist_category" required>
                        <option value="" disabled selected>Select Artist Category</option>
                        <option value="singer">Singer</option>
                        <option value="dancing">Dancing Group</option>
                        <option value="band">Music Band</option>
                    </select>
                </div>

                <div class="form-group">
                    <input type="text" name="artist_name" placeholder="Name" required>
                </div>

                <div class="form-group">
                    <input type="email" name="artist_email" placeholder="Email address" required>
                </div>

                <div class="form-group">
                    <input type="tel" name="artist_phone" placeholder="Telephone Number" required>
                </div>

                <div class="form-group">
                    <input type="number" name="perform_count" placeholder="Number of Performing Members" required min="1">
                </div>

                <div class="form-group">
                    <input type="number" name="offstage_count" placeholder="Number of Offstage Members" required min="0">
                </div>

                <div class="form-group">
                    <input type="text" name="artist_language" placeholder="Language(s)" required>
                </div>

                <div class="form-group password-container">
                    <input type="password" name="artist_pwd" placeholder="Password" required>
                    <button type="button" class="toggle-password">
                        <span class="material-icons visibility-icon">visibility_off</span>
                    </button>
                </div>

                <div class="form-group password-container">
                    <input type="password" name="artist_re_pwd" placeholder="Confirm Password" required>
                    <button type="button" class="toggle-password">
                        <span class="material-icons visibility-icon">visibility_off</span>
                    </button>
                </div>

                <div class="form-group" style="display: flex; align-items: center; font-size: 14px;">
                    <input type="checkbox" id="agree" name="agree" style="margin-right: 10px;">
                    <label for="agree">I agree to the <a href="#">terms and conditions</a></label>
                </div>

                <button type="submit" class="signup-btn">Sign Up</button>

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
                    <p>Have an account? <a href="artistlogin.php">Login</a></p>
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

        // Back button functionality
        document.querySelector('.back-button').addEventListener('click', () => {
            window.location.href = 'signupselection.php';
        });
    </script>
</body>
</html> 