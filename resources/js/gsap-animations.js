//these animations only apply on the home page
// other pages shall require their own gsap animations accordingly

import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { TextPlugin }    from 'gsap/TextPlugin'

gsap.registerPlugin(ScrollTrigger, TextPlugin)

export function initHomeAnimations() {

  // ── HERO entrance ──
  const heroTl = gsap.timeline({ defaults: { ease: 'power3.out' } })

  heroTl
    .from('.hero-label', { opacity: 0, y: 16, duration: 0.5 })
    .from('.hero-headline', {
      opacity: 0, y: 40, duration: 0.8,
      clipPath: 'inset(0 0 100% 0)',
    }, '-=0.2')
    .from('.hero-sub', { opacity: 0, y: 20, duration: 0.6 }, '-=0.4')
    .from('.search-bar', {
      opacity: 0, y: 20, scale: 0.97, duration: 0.5
    }, '-=0.3')
    .from('.stats-row .stat-item', {
      opacity: 0, y: 16, stagger: 0.1, duration: 0.4
    }, '-=0.2')
    .from('.popular-row .pop-tag', {
      opacity: 0, scale: 0.9, stagger: 0.06, duration: 0.35
    }, '-=0.2')


  // ── BENTO GRID staggered reveal ──
  gsap.from('.bento-card', {
    scrollTrigger: {
      trigger: '.bento',
      start: 'top 80%',
    },
    opacity: 0,
    y: 40,
    scale: 0.97,
    stagger: {
      amount: 0.6,
      from: 'start',
      grid: 'auto',
    },
    duration: 0.7,
    ease: 'power3.out',
  })


  // ── STAT COUNTER animation ──
  document.querySelectorAll('[data-count]').forEach(el => {
    const target  = parseFloat(el.dataset.count)
    const suffix  = el.dataset.suffix || ''
    const isFloat = el.dataset.float === 'true'

    ScrollTrigger.create({
      trigger: el,
      start: 'top 85%',
      once: true,
      onEnter() {
        gsap.to({ val: 0 }, {
          val: target,
          duration: 1.6,
          ease: 'power2.out',
          onUpdate() {
            el.textContent = isFloat
              ? this.targets()[0].val.toFixed(1) + suffix
              : Math.floor(this.targets()[0].val).toLocaleString() + suffix
          }
        })
      }
    })
  })


  // ── HOW IT WORKS steps ──
  gsap.from('.step-card', {
    scrollTrigger: {
      trigger: '.steps-grid',
      start: 'top 75%',
    },
    opacity: 0,
    x: -30,
    stagger: 0.15,
    duration: 0.6,
    ease: 'power2.out',
  })


  // ── JOB ITEM hover shimmer ──
  document.querySelectorAll('.job-item').forEach(el => {
    el.addEventListener('mouseenter', () => {
      gsap.to(el, { x: 4, duration: 0.2, ease: 'power2.out' })
    })
    el.addEventListener('mouseleave', () => {
      gsap.to(el, { x: 0, duration: 0.3, ease: 'elastic.out(1, 0.5)' })
    })
  })


  // ── LOGO STRIP infinite scroll ──
  const strip = document.querySelector('.logos-strip')
  if (strip) {
    const clone = strip.cloneNode(true)
    strip.parentNode.appendChild(clone)
    gsap.to([strip, clone], {
      x: `-=${strip.offsetWidth}`,
      repeat: -1,
      ease: 'none',
      duration: 20,
      modifiers: {
        x: gsap.utils.unitize(x => parseFloat(x) % strip.offsetWidth)
      }
    })
  }


  // ── SECTION FADE ──
  gsap.utils.toArray('.fade-section').forEach(section => {
    gsap.from(section, {
      scrollTrigger: { trigger: section, start: 'top 80%' },
      opacity: 0,
      y: 32,
      duration: 0.7,
      ease: 'power2.out',
    })
  })


  // ── MAP PINS pulse ──
  gsap.to('.map-pin', {
    scale: 1.3,
    repeat: -1,
    yoyo: true,
    duration: 1.2,
    stagger: 0.4,
    ease: 'sine.inOut',
  })
}
