// Handle sidebar navigation clicks
document.addEventListener('DOMContentLoaded', function() {
    // Get all sidebar links
    const sidebarLinks = document.querySelectorAll('.sidebar-link a');
    
    // Add click event listeners to each link
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Get the href attribute
            const href = this.getAttribute('href');
            
            // If it's the logout link
            if (href === '#') {
                e.preventDefault(); // Prevent default navigation
                
                // Show confirmation dialog
                if (confirm('Are you sure you want to logout?')) {
                    // Clear any stored data
                    localStorage.removeItem('profilePicture');
                    // Redirect to login page
                    window.location.href = 'login.html';
                }
            }
            // For other links, let them navigate normally
            // The active state is handled by the HTML class
        });
    });

    // Add active class to current page link
    const currentPage = window.location.pathname.split('/').pop();
    sidebarLinks.forEach(link => {
        const linkHref = link.getAttribute('href');
        if (linkHref === currentPage) {
            link.parentElement.classList.add('active');
        } else {
            link.parentElement.classList.remove('active');
        }
    });

    // Profile Picture Update Functionality
    const profilePictureInput = document.getElementById('profile-picture-input');
    const mainProfilePicture = document.getElementById('main-profile-picture');
    
    // Function to update all profile pictures
    window.updateProfilePictures = function(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Update main profile picture if it exists (profile page)
            if (mainProfilePicture) {
                mainProfilePicture.src = e.target.result;
            }
            
            // Update all navbar profile pictures
            const navbarProfilePics = document.querySelectorAll('.navbar-profile-pic');
            navbarProfilePics.forEach(pic => {
                pic.src = e.target.result;
            });
            
            // Store the image in localStorage for persistence
            localStorage.setItem('profilePicture', e.target.result);
        };
        reader.readAsDataURL(file);
    };

    // Handle profile picture click if on profile page
    const profilePictureContainer = document.querySelector('.profile-picture-container');
    if (profilePictureContainer && profilePictureInput) {
        profilePictureContainer.addEventListener('click', function() {
            profilePictureInput.click();
        });

        // Handle file selection
        profilePictureInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];
                
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file');
                    return;
                }
                
                // Validate file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Image size should be less than 5MB');
                    return;
                }
                
                updateProfilePictures(file);
            }
        });
    }

    // Load saved profile picture on page load
    const savedPicture = localStorage.getItem('profilePicture');
    if (savedPicture) {
        // Update main profile picture if it exists (profile page)
        if (mainProfilePicture) {
            mainProfilePicture.src = savedPicture;
        }
        
        // Update all navbar profile pictures
        const navbarProfilePics = document.querySelectorAll('.navbar-profile-pic');
        navbarProfilePics.forEach(pic => {
            pic.src = savedPicture;
        });
    }
}); 