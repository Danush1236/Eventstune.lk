<?php
include_once '../include/session.php';
include_once '../include/dbcon.php';
$response = include_once '../include/get_artist_profile.php';
$artist = null;
if ($response && $response['success'] && isset($response['data'])) {
    $artist = $response['data'];
}

// Fetch confirmed bookings for the logged-in artist
$bookings = [];
if (isset($artist['artist_id'])) {
    $stmt = $link->prepare("
        SELECT b.*, o.organizer_name, ap.package_type 
        FROM booking b
        JOIN organizer o ON b.organizer_id = o.organizer_id
        JOIN `artist package` ap ON b.package_id = ap.package_id
        WHERE b.artist_id = ? AND b.status = 'confirmed'
        ORDER BY b.event_date ASC
    ");
    $stmt->bind_param("i", $artist['artist_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    $stmt->close();
}

// Convert bookings to JSON for JavaScript
$bookingsJson = json_encode($bookings);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Calendar</title>
    <link rel="stylesheet" href="artistcalendar.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="artistnav.css">
    <link rel="stylesheet" href="popup.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <h1>EvntsTunes.lk</h1>
        </div>
        
        <div class="nav-right">
            <div class="menu" id="menu">
                <a href="#">Singers</a>
                <a href="#">Music Bands</a>
                <a href="#">Dancing Teams</a>
            </div>
            
            <div class="navbar-profile">
                <img src="../include/artist_image.php?id=<?php echo $artist['artist_id']; ?>&t=<?php echo time(); ?>" alt="Profile Picture" class="navbar-profile-pic">
            </div>
        </div>
        
        <button class="mobile-toggle" id="mobile-toggle">
            ☰
        </button>
    </nav>

    <div class="container">
        <!-- Left Sidebar -->
        <div class="sidebar">
            <h2>Manage My Account</h2>
            
            <div class="sidebar-link">
                <a href="artistprofile.php"><i class="fas fa-user"></i> My Profile</a>
            </div>
            <div class="sidebar-link">
                <a href="artistbankdetails.php"><i class="fas fa-university"></i> Bank Details</a>
            </div>
            <div class="sidebar-link">
                <a href="artistpackage.php"><i class="fas fa-box"></i> My Package</a>
            </div>
            
            <h2>Activities</h2>
            
            <div class="sidebar-link">
                <a href="artistbookings.php"><i class="fas fa-calendar-check"></i> Bookings</a>
            </div>
            <div class="sidebar-link">
                <a href="artistrequests.php"><i class="fas fa-clipboard-list"></i> Requests</a>
            </div>
            <div class="sidebar-link">
                <a href="artistcancellations.php"><i class="fas fa-ban"></i> Cancellations</a>
            </div>
            <div class="sidebar-link active">
                <a href="artistcalendar.php"><i class="fas fa-calendar-alt"></i> Calendar</a>
            </div>

            <div class="sidebar-link logout">
                <a href="../include/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="main-content">
            <h1>My Calendar</h1>
            <div class="welcome-message">
                <p>View your booked dates and event schedule</p>
            </div>
            
            <div class="calendar-container">
                <div class="calendar-header">
                    <button class="nav-btn" id="prevMonth"><i class="fas fa-chevron-left"></i></button>
                    <h2 id="currentMonth">March 2024</h2>
                    <button class="nav-btn" id="nextMonth"><i class="fas fa-chevron-right"></i></button>
                </div>
                
                <div class="calendar-grid">
                    <div class="weekday">Sun</div>
                    <div class="weekday">Mon</div>
                    <div class="weekday">Tue</div>
                    <div class="weekday">Wed</div>
                    <div class="weekday">Thu</div>
                    <div class="weekday">Fri</div>
                    <div class="weekday">Sat</div>
                    
                    <!-- Calendar days will be populated by JavaScript -->
                </div>

                <div class="event-details" id="eventDetails">
                    <h3>Event Details</h3>
                    <div class="event-info">
                        <p id="noEventMessage">Select a date to view event details</p>
                        <div id="eventContent" style="display: none;">
                            <p><strong>Booking ID:</strong> <span id="bookingId"></span></p>
                            <p><strong>Organizer:</strong> <span id="organizerName"></span></p>
                            <p><strong>Event:</strong> <span id="eventName"></span></p>
                            <p><strong>Location:</strong> <span id="eventLocation"></span></p>
                            <p><strong>Time:</strong> <span id="eventTime"></span></p>
                            <p><strong>Participants:</strong> <span id="participantCount"></span></p>
                            <p><strong>Package:</strong> <span id="packageType"></span></p>
                            <p><strong>Amount:</strong> <span id="totalAmount"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="navbar.js"></script>
    <script src="artistnav.js"></script>
    <script>
        // Get bookings data from PHP
        const bookings = <?php echo $bookingsJson; ?>;
        
        // Calendar functionality
        const calendar = {
            currentDate: new Date(),
            bookingsMap: new Map(),
            today: new Date(),

            init() {
                this.setupBookingsMap();
                this.renderCalendar();
                this.setupEventListeners();
            },

            setupBookingsMap() {
                bookings.forEach(booking => {
                    const date = booking.event_date;
                    if (!this.bookingsMap.has(date)) {
                        this.bookingsMap.set(date, []);
                    }
                    this.bookingsMap.get(date).push(booking);
                });
            },

            renderCalendar() {
                const year = this.currentDate.getFullYear();
                const month = this.currentDate.getMonth();

                // Update month/year display
                document.getElementById('currentMonth').textContent = 
                    new Date(year, month).toLocaleString('default', { month: 'long', year: 'numeric' });

                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const daysInMonth = lastDay.getDate();
                const startingDay = firstDay.getDay();

                const calendarGrid = document.querySelector('.calendar-grid');
                const daysContainer = document.createElement('div');
                daysContainer.className = 'days-container';
                
                // Clear previous days
                const existingDays = calendarGrid.querySelectorAll('.calendar-day');
                existingDays.forEach(day => day.remove());

                // Add empty cells for days before the first of the month
                for (let i = 0; i < startingDay; i++) {
                    const emptyDay = document.createElement('div');
                    emptyDay.className = 'calendar-day empty';
                    calendarGrid.appendChild(emptyDay);
                }

                // Add days of the month
                for (let day = 1; day <= daysInMonth; day++) {
                    const dayCell = document.createElement('div');
                    dayCell.className = 'calendar-day';
                    
                    const currentDate = new Date(year, month, day);
                    const dateString = currentDate.toISOString().split('T')[0];
                    
                    // Check if this is today's date
                    if (this.isToday(currentDate)) {
                        dayCell.classList.add('today');
                    }
                    
                    // Check if there are events on this day
                    if (this.bookingsMap.has(dateString)) {
                        dayCell.classList.add('has-event');
                        dayCell.setAttribute('data-date', dateString);
                    }
                    
                    dayCell.textContent = day;
                    calendarGrid.appendChild(dayCell);
                }
            },

            isToday(date) {
                return date.getDate() === this.today.getDate() &&
                       date.getMonth() === this.today.getMonth() &&
                       date.getFullYear() === this.today.getFullYear();
            },

            setupEventListeners() {
                // Previous month button
                document.getElementById('prevMonth').addEventListener('click', () => {
                    this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                    this.renderCalendar();
                });

                // Next month button
                document.getElementById('nextMonth').addEventListener('click', () => {
                    this.currentDate.setMonth(this.currentDate.getMonth() + 1);
                    this.renderCalendar();
                });

                // Event delegation for day clicks
                document.querySelector('.calendar-grid').addEventListener('click', (e) => {
                    if (e.target.classList.contains('calendar-day')) {
                        const date = e.target.getAttribute('data-date');
                        if (date) {
                            this.showEventDetails(date);
                        } else {
                            this.hideEventDetails();
                        }
                    }
                });
            },

            showEventDetails(date) {
                const events = this.bookingsMap.get(date);
                const noEventMessage = document.getElementById('noEventMessage');
                const eventContent = document.getElementById('eventContent');
                
                if (events && events.length > 0) {
                    // Show first event for the day (can be enhanced to show multiple events)
                    const event = events[0];
                    
                    document.getElementById('bookingId').textContent = '#BK' + String(event.booking_id).padStart(3, '0');
                    document.getElementById('organizerName').textContent = event.organizer_name;
                    document.getElementById('eventName').textContent = event.occasion;
                    document.getElementById('eventLocation').textContent = event.event_location;
                    document.getElementById('eventTime').textContent = event.event_time;
                    document.getElementById('participantCount').textContent = event.participant_count;
                    document.getElementById('packageType').textContent = event.package_type;
                    document.getElementById('totalAmount').textContent = 'Rs. ' + Number(event.total_amount).toLocaleString();
                    
                    noEventMessage.style.display = 'none';
                    eventContent.style.display = 'block';
                }
            },

            hideEventDetails() {
                document.getElementById('noEventMessage').style.display = 'block';
                document.getElementById('eventContent').style.display = 'none';
            }
        };

        // Initialize calendar when page loads
        document.addEventListener('DOMContentLoaded', () => {
            calendar.init();
        });
    </script>

    <style>
        .calendar-day {
            cursor: pointer;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            position: relative;
        }

        .calendar-day.has-event {
            background-color: #e3f2fd;
            color: #1976d2;
            font-weight: bold;
        }

        .calendar-day.has-event:hover {
            background-color: #bbdefb;
        }

        .calendar-day.today {
            border: 2px solid #f44336;
            color: #f44336;
            font-weight: bold;
        }

        .calendar-day.today.has-event {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .calendar-day.today::after {
            content: "Today";
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 10px;
            background: #f44336;
            color: white;
            padding: 2px 6px;
            border-radius: 10px;
        }

        .calendar-day.empty {
            background-color: #f5f5f5;
        }

        .event-details {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .event-info p {
            margin: 10px 0;
            line-height: 1.5;
        }

        .event-info strong {
            color: #1976d2;
            min-width: 100px;
            display: inline-block;
        }
    </style>

    <script src="popup.js"></script>
    
    <script>
        // Load saved profile picture on page load
        window.addEventListener('load', function() {
            const savedPicture = localStorage.getItem('profilePicture');
            if (savedPicture) {
                const navbarProfilePic = document.querySelector('.navbar-profile-pic');
                if (navbarProfilePic) {
                    navbarProfilePic.src = savedPicture;
                }
            }
        });
    </script>

    <link rel="stylesheet" href="footer.css">
    <footer class="footer">
        <div class="footer-main">
            <div class="footer-left">
                <div class="footer-logo">
                    <span>EventsTune.lk</span>
                </div>
                <p class="footer-info">
                    Your premier destination for booking talented singers and performers. 
                    We connect artists with opportunities, making entertainment accessible to all.
                </p>
                <div class="footer-socials">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            
            <div class="footer-links">
                <div class="footer-section">
                    <h4><i class="fas fa-sitemap"></i> Site Map</h4>
                    <ul>
                        <li><a href="#"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="#"><i class="fas fa-info-circle"></i> About Us</a></li>
                        <li><a href="#"><i class="fas fa-envelope"></i> Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4><i class="fas fa-question-circle"></i> FAQ & Support</h4>
                    <ul>
                        <li><a href="#"><i class="fas fa-book"></i> How to Book</a></li>
                        <li><a href="#"><i class="fas fa-credit-card"></i> Payment Methods</a></li>
                        <li><a href="#"><i class="fas fa-ban"></i> Cancellation Policy</a></li>
                        <li><a href="#"><i class="fas fa-shield-alt"></i> Privacy Policy</a></li>
                        <li><a href="#"><i class="fas fa-file-contract"></i> Terms of Service</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4><i class="fas fa-address-book"></i> Contact Info</h4>
                    <ul>
                        <li><a href="tel:+1234567890"><i class="fas fa-phone-alt"></i> +123 456 7890</a></li>
                        <li><a href="mailto:info@eventstune.lk"><i class="fas fa-envelope"></i> info@eventstune.lk</a></li>
                        <li><a href="#"><i class="fas fa-map-marker-alt"></i> 123 Event Street, Colombo</a></li>
                        <li><a href="#"><i class="fas fa-clock"></i> Mon - Sat: 9:00 AM - 6:00 PM</a></li>
                        <li><a href="#"><i class="fas fa-globe"></i> www.eventstune.lk</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="footer-bottom-links">
                <a href="#"><i class="fas fa-shield-alt"></i> Privacy Policy</a>
                <a href="#"><i class="fas fa-file-contract"></i> Terms of Service</a>
                <a href="#"><i class="fas fa-cookie"></i> Cookie Policy</a>
            </div>
            <span>Copyright 2025 © EventsTune.lk | All Rights Reserved</span>
        </div>
    </footer>
</body>
</html> 