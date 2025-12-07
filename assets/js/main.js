/**
 * Main JavaScript
 * Maysan Al-Riyidh CCTV Security Systems
 */

// Get current language
function getCurrentLanguage() {
    return document.documentElement.classList.contains('ar') ? 'ar' : 'en';
}

// Get current direction
function getDirection() {
    return document.documentElement.getAttribute('dir') || 'ltr';
}

// Switch language
function switchLanguage(lang) {
    // Send AJAX request to switch language
    fetch('/maysan/api/switch-language.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ language: lang })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Toggle user menu
function toggleUserMenu() {
    const menu = document.getElementById('userMenuDropdown');
    if (menu) {
        menu.classList.toggle('active');
    }
}

// Toggle sidebar
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        sidebar.classList.toggle('active');
    }
}

// Close user menu when clicking outside
document.addEventListener('click', function(event) {
    const userMenu = document.getElementById('userMenuDropdown');
    const userMenuToggle = document.querySelector('.user-menu-toggle');
    
    if (userMenu && userMenuToggle) {
        if (!userMenu.contains(event.target) && !userMenuToggle.contains(event.target)) {
            userMenu.classList.remove('active');
        }
    }
});

// Modal functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
});

// Close modal with close button
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal-close')) {
        const modal = event.target.closest('.modal');
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    }
});

// Show alert
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = `
        <span>${message}</span>
    `;
    
    const container = document.querySelector('.main-content') || document.body;
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Confirm delete
function confirmDelete(message = 'Are you sure you want to delete this record?') {
    return confirm(message);
}

// Format date
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString(getCurrentLanguage() === 'ar' ? 'ar-SA' : 'en-US', options);
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat(getCurrentLanguage() === 'ar' ? 'ar-SA' : 'en-US', {
        style: 'currency',
        currency: 'SAR'
    }).format(amount);
}

// Validate email
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Validate form
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = 'var(--danger-color)';
            isValid = false;
        } else {
            input.style.borderColor = '';
        }
        
        // Email validation
        if (input.type === 'email' && input.value && !validateEmail(input.value)) {
            input.style.borderColor = 'var(--danger-color)';
            isValid = false;
        }
    });
    
    return isValid;
}

// AJAX request helper
function ajaxRequest(url, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        }
    };
    
    if (data && method !== 'GET') {
        options.body = JSON.stringify(data);
    }
    
    return fetch(url, options).then(response => response.json());
}

// Get form data as object
function getFormData(formId) {
    const form = document.getElementById(formId);
    if (!form) return null;
    
    const formData = new FormData(form);
    const data = {};
    
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    return data;
}

// Logout
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        window.location.href = '/maysan/api/logout.php';
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Add any initialization code here
    console.log('Maysan Al-Riyidh Security System Loaded');
});
