import { animate, stagger } from 'animejs';

export function initAnimations() {
    // Animasi Fade In
    if (document.querySelector('.animasi-fade')) {
        animate('.animasi-fade', {
            opacity: [0, 1],
            delay: stagger(100),
            duration: 500,
            easing: 'easeInOutQuad'
        });
    }

    // Animasi Slide Up
    if (document.querySelector('.animasi-slide')) {
        animate('.animasi-slide', {
            translateY: [40, 0],
            opacity: [0, 1],
            delay: stagger(100),
            duration: 500,
            easing: 'easeOutExpo'
        });
    }
}
