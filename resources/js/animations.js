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
    if (document.querySelector('.animasi-slide-keatas')) {
        animate('.animasi-slide-keatas', {
            translateY: [40, 0],
            opacity: [0, 1],
            delay: stagger(100),
            duration: 500,
            easing: 'easeOutExpo'
        });
    }

    // Animasi Slide In dari Sisi Kanan
    if (document.querySelector('.animasi-slide-kekiri')) {
        animate('.animasi-slide-kekiri', {
            translateX: [100, 0],
            opacity: [0, 1],
            delay: stagger(100),
            duration: 500,
            easing: 'easeOutExpo'
        });
    }

    // Animasi Slide In dari Sisi Kiri
    if (document.querySelector('.animasi-slide-kekanan')) {
        animate('.animasi-slide-kekanan', {
            translateX: [-100, 0],
            opacity: [0, 1],
            delay: stagger(100),
            duration: 500,
            easing: 'easeOutExpo'
        });
    }
}
