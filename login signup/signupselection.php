<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventsTune.lk</title>
    <link rel="stylesheet" href="signupselection.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <h1>EventsTunes.lk</h1>
        </div>
        
        <div class="menu" id="menu">
            <a href="#">Singers</a>
            <a href="#">Music Bands</a>
            <a href="#">Dancing Teams</a>
            <div class="auth-buttons">
                <button class="login-btn">Log In</button>
                <button class="signup-btn">Sign Up</button>
            </div>
        </div>
        
        <button class="mobile-toggle" id="mobile-toggle">
            ☰
        </button>
    </nav>
    <div class="background-overlay">
        <div class="container">
            <div class="welcome-text">
                <h1>Welcome to EventsTunes.lk</h1>
                <p>Your one-stop platform for connecting talented artists with event organizers. Choose your role to get started.</p>
            </div>
            <div class="roles">
                <a href="artistsignup.php" class="role-card">
                    <h2>Artists</h2>
                    <p>
                        Sign up as an artist to manage your performances, update your profile, view bookings, and respond to event requests from organizers.
                    </p>
                </a>
                <a href="organizersignup.php" class="role-card">
                    <h2>Organizer</h2>
                    <p>
                        Sign up as an organizer to find and book artists, manage your event schedules, and track your booking history for smooth event planning.
                    </p>
                </a>
            </div>
        </div>
    </div>
    <script>
        // Toggle mobile menu
        const mobileToggle = document.getElementById('mobile-toggle');
        const menu = document.getElementById('menu');
        
        mobileToggle.addEventListener('click', () => {
            menu.classList.toggle('active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!menu.contains(e.target) && !mobileToggle.contains(e.target) && menu.classList.contains('active')) {
                menu.classList.remove('active');
            }
        });

        // Add hover effects for the logo and menu items
        const menuItems = document.querySelectorAll('.menu a');

        menuItems.forEach(item => {
            item.addEventListener('mouseover', () => {
                item.style.letterSpacing = '1px';
            });
            
            item.addEventListener('mouseout', () => {
                item.style.letterSpacing = '0';
            });
        });
    </script>
</body>
</html>