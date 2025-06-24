// public/js/script.js

document.addEventListener('DOMContentLoaded', () => {
    console.log("Custom script.js loaded!");

    // Smooth scrolling for navigation links
    const navLinks = document.querySelectorAll('.navbar-nav a[href^="#"], footer a[href^="#"]');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const hrefAttribute = this.getAttribute('href');
            // Ensure it's a local link and not just "#"
            if (hrefAttribute.startsWith('#') && hrefAttribute.length > 1) {
                e.preventDefault();
                const targetId = hrefAttribute.substring(1);
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    // Active navigation link highlighting based on scroll position
    const sections = document.querySelectorAll('section[id]');
    const navbarLinks = document.querySelectorAll('.navbar-nav a');

    function changeLinkState() {
        let index = sections.length;

        while(--index && window.scrollY + 50 < sections[index].offsetTop) {}

        navbarLinks.forEach((link) => link.classList.remove('active'));
        // Check if a corresponding link exists before trying to add 'active' class
        if (navbarLinks[index]) {
             // Ensure the link is for a section on the current page
            const correspondingSectionId = navbarLinks[index].getAttribute('href');
            if (correspondingSectionId && correspondingSectionId.startsWith('#') && document.getElementById(correspondingSectionId.substring(1))) {
                navbarLinks[index].classList.add('active');
            }
        }
    }
    // Initial call if page is loaded scrolled or has a hash
    if (sections.length > 0) {
        changeLinkState();
        window.addEventListener('scroll', changeLinkState);
    }


    // Simple Testimonial Slider (if multiple items exist)
    const testimonialSlider = document.querySelector('.testimonial-slider');
    if (testimonialSlider) {
        const testimonials = testimonialSlider.querySelectorAll('.testimonial-item');
        if (testimonials.length > 1) {
            let currentTestimonial = 0;
            testimonials[currentTestimonial].style.display = 'block'; // Show first

            // Function to show next testimonial
            function nextTestimonial() {
                testimonials[currentTestimonial].style.display = 'none';
                currentTestimonial = (currentTestimonial + 1) % testimonials.length;
                testimonials[currentTestimonial].style.display = 'block';
            }

            // Auto-play testimonials
            // setInterval(nextTestimonial, 5000); // Change every 5 seconds

            // If you want manual controls (Next/Prev buttons), you'd add them here
            // For now, keeping it simple with auto-play or just showing the first one
            // if no JS based slider functionality is built.
            // For this example, let's just cycle them.
             setInterval(nextTestimonial, 7000); // Cycle every 7 seconds
        } else if (testimonials.length === 1) {
            testimonials[0].style.display = 'block'; // Ensure the single testimonial is visible
        }
    }


    // Dynamic study elements animation - more controlled via JS if needed
    // The CSS animation is already good, but JS could be used for more complex scenarios
    // like varying speeds, directions, or adding/removing elements dynamically.
    // For now, the CSS animation is sufficient.

    console.log("Interactive features initialized.");
});
