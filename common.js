// Common JavaScript for The Malvar Bat Cave Cafe

// Toggle Profile Dropdown
function toggleProfile() {
    const menu = document.getElementById('profileMenu');
    const cartMenu = document.getElementById('cartMenu');
    
    if (menu) {
        menu.classList.toggle('active');
    }
    
    if (cartMenu) {
        cartMenu.classList.remove('active');
    }
}

// Toggle Cart Dropdown
function toggleCart() {
    const menu = document.getElementById('cartMenu');
    const profileMenu = document.getElementById('profileMenu');
    
    if (menu) {
        menu.classList.toggle('active');
    }
    
    if (profileMenu) {
        profileMenu.classList.remove('active');
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const profileDropdown = document.querySelector('.profile-dropdown');
    const cartDropdown = document.querySelector('.cart-dropdown');
    const profileMenu = document.getElementById('profileMenu');
    const cartMenu = document.getElementById('cartMenu');
    
    if (profileDropdown && profileMenu && !profileDropdown.contains(event.target)) {
        profileMenu.classList.remove('active');
    }
    
    if (cartDropdown && cartMenu && !cartDropdown.contains(event.target)) {
        cartMenu.classList.remove('active');
    }
});

// Logout function
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        window.location.href = 'login.php?logout=1';
    }
}

// Update cart quantity
function updateQuantity(index, change) {
    fetch('update-cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `index=${index}&change=${change}`
    })
    .then(response => response.json())
    .then(() => {
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        location.reload();
    });
}

// Checkout
function checkout() {
    alert('Checkout functionality will be implemented soon!');
    // window.location.href = 'checkout.php';
}

// Copy GCash number
function copyGcashNumber() {
    const number = '09636996688';
    if (navigator.clipboard) {
        navigator.clipboard.writeText(number).then(() => {
            alert('GCash number copied to clipboard!');
        }).catch(() => {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = number;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('GCash number copied to clipboard!');
        });
    }
}

// Preview image for file upload
function previewImage(input) {
    const preview = document.getElementById('preview');
    const previewDiv = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (preview) {
                preview.src = e.target.result;
            }
            if (previewDiv) {
                previewDiv.style.display = 'block';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Close modal
function closeModal() {
    const modal = document.getElementById('successModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Search menu items
function searchMenu() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const menuCards = document.querySelectorAll('.menu-card, .product-card');
    
    menuCards.forEach(card => {
        const title = card.querySelector('.menu-title, h3');
        const description = card.querySelector('.menu-description, p');
        
        if (title) {
            const titleText = title.textContent || title.innerText;
            const descText = description ? (description.textContent || description.innerText) : '';
            const searchText = (titleText + ' ' + descText).toLowerCase();
            
            if (searchText.indexOf(filter) > -1) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        }
    });
    
    // Show/hide category titles if all items are hidden
    const categoryTitles = document.querySelectorAll('.category-title');
    categoryTitles.forEach(title => {
        const nextGrid = title.nextElementSibling;
        if (nextGrid && nextGrid.classList.contains('menu-grid')) {
            const visibleCards = nextGrid.querySelectorAll('.menu-card:not([style*="display: none"])');
            if (visibleCards.length === 0) {
                title.style.display = 'none';
            } else {
                title.style.display = '';
            }
        }
    });
}

// Dark Mode Toggle - Simple version
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    
    // Save preference
    if (document.body.classList.contains('dark-mode')) {
        localStorage.setItem('darkMode', 'enabled');
    } else {
        localStorage.setItem('darkMode', 'disabled');
    }
    
    // Update icon
    updateDarkModeIcon();
}

// Update dark mode icon
function updateDarkModeIcon() {
    const icon = document.getElementById('darkModeIcon');
    if (!icon) return;
    
    if (document.body.classList.contains('dark-mode')) {
        icon.src = 'images/lightmode.png';
        icon.alt = 'Light Mode';
    } else {
        icon.src = 'images/darkmode.png';
        icon.alt = 'Dark Mode';
    }
}

// Initialize on load
(function() {
    // Apply saved preference immediately
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
    }
    
    // Update icon when page loads
    window.addEventListener('load', updateDarkModeIcon);
})();

// Debug: Log when script is loaded
console.log('✅ Cafe common script loaded');
