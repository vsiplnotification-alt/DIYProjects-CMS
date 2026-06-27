// DIY Projects CMS - JavaScript

// Open Certificate Form Modal
function openCertificateForm() {
    document.getElementById('certificateModal').style.display = 'block';
}

// Close Certificate Form Modal
function closeCertificateForm() {
    document.getElementById('certificateModal').style.display = 'none';
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('certificateModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Form Validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('certificateForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const contact = document.getElementById('contact').value.trim();
            const payment_id = document.getElementById('payment_id').value.trim();

            if (!name || !email || !contact || !payment_id) {
                e.preventDefault();
                alert('Please fill in all fields');
                return false;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Please enter a valid email address');
                return false;
            }

            // Phone validation (basic)
            const phoneRegex = /^[0-9]{10}$/;
            if (!phoneRegex.test(contact.replace(/[\s\-]/g, ''))) {
                e.preventDefault();
                alert('Please enter a valid 10-digit contact number');
                return false;
            }
        });
    }
});

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});