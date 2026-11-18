import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Scroll reveal observer (03-DESIGN.md Section 9)
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-revealed');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.08,
        rootMargin: '0px 0px -30px 0px'
    });

    document.querySelectorAll('.reveal-on-scroll').forEach(el => observer.observe(el));
});

