/* ============================================
   Portfolio - Main JavaScript
   Version: 1.0
   ============================================ */

document.addEventListener('DOMContentLoaded', function () {
    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Contact Form Handling
    const contactForm = document.getElementById('contact-form');
    const formMessage = document.getElementById('form-message');
    const submitBtn = document.getElementById('submit-btn');

    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';
            formMessage.classList.add('hidden');
            formMessage.classList.remove('text-green-600', 'text-red-600');

            const formData = new FormData(contactForm);

            fetch('contact.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    formMessage.textContent = data.message;
                    formMessage.classList.remove('hidden');

                    if (data.status === 'success') {
                        formMessage.classList.add('text-green-600');
                        contactForm.reset();
                    } else {
                        formMessage.classList.add('text-red-600');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    formMessage.textContent = 'An error occurred. Please try again later.';
                    formMessage.classList.remove('hidden');
                    formMessage.classList.add('text-red-600');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Send Message';
                });
        });
    }
});
