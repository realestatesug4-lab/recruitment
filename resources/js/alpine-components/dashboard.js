import { gsap } from 'gsap';

export default function dashboard() {
  return {
    animateCards() {
      gsap.from('.dashboard-card', {
        duration: 0.75,
        opacity: 0,
        y: 20,
        stagger: 0.08,
        ease: 'power3.out',
      });

      gsap.from('.dashboard-chart', {
        duration: 0.85,
        opacity: 0,
        scale: 0.96,
        stagger: 0.1,
        ease: 'power3.out',
      });
    },
  };
}
