// Sample events data
const sampleEvents = {
    '2024-03-15': {
        title: 'Wedding Ceremony',
        organizer: 'John Smith',
        location: 'Grand Hotel, Colombo',
        time: '6:00 PM',
        attendees: 150,
        package: 'Gold',
        amount: 'Rs. 75,000'
    },
    '2024-03-20': {
        title: 'Corporate Event',
        organizer: 'Sarah Johnson',
        location: 'Convention Center',
        time: '2:00 PM',
        attendees: 200,
        package: 'Platinum',
        amount: 'Rs. 150,000'
    },
    '2024-03-25': {
        title: 'Birthday Party',
        organizer: 'Maria Garcia',
        location: 'Beach Resort',
        time: '7:00 PM',
        attendees: 50,
        package: 'Silver',
        amount: 'Rs. 45,000'
    },
    '2024-04-01': {
        title: 'Music Festival',
        organizer: 'David Wilson',
        location: 'City Stadium',
        time: '4:00 PM',
        attendees: 500,
        package: 'Platinum',
        amount: 'Rs. 200,000'
    },
    '2024-04-05': {
        title: 'Gala Dinner',
        organizer: 'Emma Thompson',
        location: 'Luxury Hotel',
        time: '8:00 PM',
        attendees: 100,
        package: 'Gold',
        amount: 'Rs. 90,000'
    }
};

// Calendar functionality
document.addEventListener('DOMContentLoaded', function() {
    const calendarGrid = document.querySelector('.calendar-grid');
    const currentMonthElement = document.getElementById('currentMonth');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    const eventDetails = document.getElementById('eventDetails');

    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();

    // Function to update calendar
    function updateCalendar() {
        const firstDay = new Date(currentYear, currentMonth, 1);
        const lastDay = new Date(currentYear, currentMonth + 1, 0);
        const startingDay = firstDay.getDay();
        const totalDays = lastDay.getDate();
        const prevMonthDays = new Date(currentYear, currentMonth, 0).getDate();

        // Update month and year display
        currentMonthElement.textContent = `${firstDay.toLocaleString('default', { month: 'long' })} ${currentYear}`;

        // Clear existing calendar days
        const weekdayElements = document.querySelectorAll('.weekday');
        weekdayElements.forEach(day => day.remove());
        calendarGrid.innerHTML = '';

        // Add weekday headers
        const weekdayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        weekdayNames.forEach(day => {
            const weekdayElement = document.createElement('div');
            weekdayElement.className = 'weekday';
            weekdayElement.textContent = day;
            calendarGrid.appendChild(weekdayElement);
        });

        // Add previous month days
        for (let i = startingDay - 1; i >= 0; i--) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day other-month';
            dayElement.textContent = prevMonthDays - i;
            calendarGrid.appendChild(dayElement);
        }

        // Add current month days
        for (let day = 1; day <= totalDays; day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            dayElement.textContent = day;

            // Check if it's today
            if (day === currentDate.getDate() && 
                currentMonth === currentDate.getMonth() && 
                currentYear === currentDate.getFullYear()) {
                dayElement.classList.add('today');
            }

            // Check if it's a booked date
            const dateString = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            if (sampleEvents[dateString]) {
                dayElement.classList.add('booked');
                dayElement.addEventListener('click', () => showEventDetails(dateString));
            }

            calendarGrid.appendChild(dayElement);
        }

        // Add next month days
        const totalCells = 42; // 6 rows * 7 days
        const remainingCells = totalCells - (startingDay + totalDays);
        for (let i = 1; i <= remainingCells; i++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day other-month';
            dayElement.textContent = i;
            calendarGrid.appendChild(dayElement);
        }
    }

    // Function to show event details
    function showEventDetails(dateString) {
        const event = sampleEvents[dateString];
        if (event) {
            eventDetails.innerHTML = `
                <h3>Event Details</h3>
                <div class="event-info">
                    <p><strong>Event:</strong> ${event.title}</p>
                    <p><strong>Organizer:</strong> ${event.organizer}</p>
                    <p><strong>Location:</strong> ${event.location}</p>
                    <p><strong>Time:</strong> ${event.time}</p>
                    <p><strong>Attendees:</strong> ${event.attendees}</p>
                    <p><strong>Package:</strong> ${event.package}</p>
                    <p><strong>Amount:</strong> ${event.amount}</p>
                </div>
            `;
        }
    }

    // Event listeners for month navigation
    prevMonthBtn.addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        updateCalendar();
    });

    nextMonthBtn.addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        updateCalendar();
    });

    // Initial calendar render
    updateCalendar();
}); 