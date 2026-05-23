// top-nav.js — hide on scroll down, reveal on scroll up
export function init() {
    const headers = document.querySelectorAll('[ak-top-nav]');
    if (!headers[0]) return;

    let lastScrollY = window.scrollY;

    const update = () => {
        const currentScrollY = window.scrollY;
        const goingDown = currentScrollY > lastScrollY && currentScrollY > 80;

        headers.forEach((el) => {
            if (goingDown) {
                el.classList.add('-translate-y-full');
            } else {
                el.classList.remove('-translate-y-full');
            }

            if (currentScrollY === 0) {
                el.classList.add('bg-transparent');
                el.classList.remove('bg-white/50');
                el.classList.remove('backdrop-blur-sm');
            } else if (currentScrollY > 100) {
                el.classList.remove('bg-transparent');
                el.classList.add('bg-white/50');
                el.classList.add('backdrop-blur-sm');
            }
        });

        lastScrollY = currentScrollY;
    };

    window.addEventListener('scroll', update, { passive: true });
}
