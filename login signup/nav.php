<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EvntsTunes.lk - Full Width Navigation Bar</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0a1128, #1282a2);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0; /* Removed padding */
        }

        .navbar {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 15px 50px; /* Increased horizontal padding */
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.36);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 100;
            max-width: 100%; /* Changed from 1200px to 100% */
        }

        .content-wrapper {
            width: 100%;
            max-width: 1200px;
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo h1 {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(45deg, #1282a2, #0077b6);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            position: relative;
            transition: all 0.3s ease;
        }

        .logo h1:hover {
            text-shadow: 0 0 10px rgba(18, 130, 162, 0.7);
            transform: scale(1.05);
        }

        .logo h1::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(45deg, #1282a2, #0077b6);
            transition: width 0.5s ease;
        }

        .logo h1:hover::after {
            width: 100%;
        }

        .menu {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .menu a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
            position: relative;
            padding: 5px 0;
            transition: all 0.3s ease;
        }

        .menu a::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: #48cae4;
            transition: width 0.3s ease;
        }

        .menu a:hover {
            color: #48cae4;
            transform: translateY(-2px);
        }

        .menu a:hover::before {
            width: 100%;
        }

        .auth-buttons {
            display: flex;
            gap: 15px;
        }

        .auth-buttons button {
            padding: 10px 18px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            outline: none;
            position: relative;
            overflow: hidden;
        }

        .login-btn {
            background: transparent;
            color: #fff;
            border: 2px solid #48cae4 !important;
        }

        .login-btn:hover {
            background: rgba(72, 202, 228, 0.1);
            box-shadow: 0 0 15px rgba(72, 202, 228, 0.6);
        }

        .signup-btn {
            background: linear-gradient(45deg, #0077b6, #023e8a);
            color: #fff;
        }

        .signup-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.7s ease;
        }

        .signup-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(0, 119, 182, 0.5);
        }

        .signup-btn:hover::before {
            left: 100%;
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 24px;
            cursor: pointer;
        }

        @media (max-width: 900px) {
            .navbar {
                padding: 15px 20px; /* Reduced padding on mobile */
            }
            
            .menu {
                position: absolute;
                top: 70px;
                left: 0;
                width: 100%;
                padding: 20px;
                background: rgba(26, 26, 46, 0.95);
                backdrop-filter: blur(10px);
                flex-direction: column;
                box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.36);
                clip-path: polygon(0 0, 100% 0, 100% 0, 0 0);
                transition: clip-path 0.5s ease-in-out;
                z-index: -1;
            }

            .menu.active {
                clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%);
            }

            .mobile-toggle {
                display: block;
            }

            .auth-buttons {
                margin-top: 15px;
                width: 100%;
                justify-content: center;
            }
        }

        /* Custom animation for menu items */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .menu a {
            opacity: 0;
            animation: fadeInUp 0.5s forwards;
        }

        .menu a:nth-child(1) {
            animation-delay: 0.2s;
        }

        .menu a:nth-child(2) {
            animation-delay: 0.3s;
        }

        .menu a:nth-child(3) {
            animation-delay: 0.4s;
        }

        .auth-buttons button {
            opacity: 0;
            animation: fadeInUp 0.5s forwards;
        }

        .login-btn {
            animation-delay: 0.5s;
        }

        .signup-btn {
            animation-delay: 0.6s;
        }

        /* Pulse animation for the logo */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        .logo h1 {
            animation: pulse 3s infinite;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <h1>EvntsTunes.lk</h1>
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

    <div class="content-wrapper">
        <!-- Your page content goes here -->
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