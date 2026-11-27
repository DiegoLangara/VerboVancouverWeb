import { gsap } from 'gsap';

/**
 * Las barras de luz se encienden desde el suelo, el travesaño forma la cruz
 * y, al hacer scroll, las barras se abren como si la cámara entrara al santuario.
 */
export function iniciarHero() {
  const hero = document.querySelector('[data-hero]');
  if (!hero) return;

  const barras = hero.querySelectorAll('.hero__barra');
  const travesano = hero.querySelector('.hero__travesano');
  const texto = hero.querySelectorAll('.hero__antetitulo, .hero__titulo, .hero__bajada, .hero__acciones');

  gsap.timeline({ defaults: { ease: 'expo.out' } })
    .from(barras, { scaleY: 0, opacity: 0, duration: 1.6, stagger: { each: 0.06, from: 'center' } })
    .from(travesano, { scaleX: 0, opacity: 0, duration: 1.2 }, '-=0.8')
    .from(texto, { y: 30, opacity: 0, duration: 1, stagger: 0.1 }, '-=0.9');

  // "Respiración" sutil de la luz.
  gsap.to(barras, {
    opacity: () => gsap.utils.random(0.35, 0.9),
    duration: () => gsap.utils.random(2, 4),
    repeat: -1, yoyo: true, ease: 'sine.inOut', stagger: { each: 0.2, from: 'random' }, delay: 2,
  });

  // Entrada al santuario con el scroll.
  gsap.to(hero.querySelector('.hero__luces'), {
    scale: 1.6, opacity: 0.25, ease: 'none',
    scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: true },
  });
}
