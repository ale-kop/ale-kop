// top-nav.js
// Toggle "shadow-xl" em elementos com o atributo [ak-top-nav] quando houver scroll
export function init() {
    const targets = document.querySelectorAll('[ak-top-nav]');
    if (!targets[0]) return;

    const toggleShadow = () => {
        const scrolled = window.scrollY > 0;
        targets.forEach((el) => {
            // smooth animation
            el.classList.add('transition-all', 'duration-300');

            if (scrolled) {
                el.classList.add('shadow-sm/10');
                el.classList.remove('py-6');
            } else {
                el.classList.add('py-6');
                el.classList.remove('shadow-sm/10');
            }
        });
    };

    toggleShadow();
    document.addEventListener('scroll', toggleShadow, { passive: true });
}
