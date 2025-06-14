import anime from 'animejs';

// Fungsi ini akan menjalankan semua animasi global
export function initAnimations() {
    // Animasi Fade In
    if (document.querySelector('.animasi-fade')) {
        anime({
            targets: '.animasi-fade',
            opacity: [0, 1],
            duration: 1200,
            easing: 'easeInOutQuad'
        });
    }

    // Animasi Slide Up
    if (document.querySelector('.animasi-slide')) {
        anime({
            targets: '.animasi-slide',
            translateY: [40, 0],
            opacity: [0, 1],
            delay: anime.stagger(150),
            duration: 900,
            easing: 'easeOutExpo'
        });
    }
}
