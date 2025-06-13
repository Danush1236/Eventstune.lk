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