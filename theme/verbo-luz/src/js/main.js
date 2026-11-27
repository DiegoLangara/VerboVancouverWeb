import '../css/main.css';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';
import { iniciarHero } from './hero.js';

gsap.registerPlugin(ScrollTrigger);

const sinMovimiento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (!sinMovimiento) {
  // Scroll suave sincronizado con ScrollTrigger.
  const lenis = new Lenis({ lerp: 0.1 });
  lenis.on('scroll', ScrollTrigger.update);
  gsap.ticker.add((t) => lenis.raf(t * 1000));
  gsap.ticker.lagSmoothing(0);

  iniciarHero();

  // Revelado general: cualquier elemento con data-revelar.
  gsap.utils.toArray('[data-revelar]').forEach((el) => {
    if (el.closest('[data-hero]')) return;
    gsap.from(el, {
      y: 40, opacity: 0, duration: 1, ease: 'expo.out',
      scrollTrigger: { trigger: el, start: 'top 85%' },
    });
  });
}
