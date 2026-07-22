import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { TextPlugin } from 'gsap/TextPlugin'

gsap.registerPlugin(ScrollTrigger, TextPlugin)

function prefersReducedMotion() {
  return window.matchMedia('(prefers-reduced-motion: reduce)').matches
}

function isMobile() {
  return window.matchMedia('(max-width: 767px)').matches
}

function bindCardPress(selector) {
  document.querySelectorAll(selector).forEach(el => {
    const press = () => gsap.to(el, { scale: 0.98, duration: 0.12, ease: 'power2.out' })
    const release = () => gsap.to(el, { scale: 1, duration: 0.25, ease: 'elastic.out(1, 0.6)' })
    el.addEventListener('touchstart', press, { passive: true })
    el.addEventListener('touchend', release, { passive: true })
    el.addEventListener('mousedown', press)
    el.addEventListener('mouseup', release)
    el.addEventListener('mouseleave', release)
  })
}

function bindHoverShift(selector) {
  document.querySelectorAll(selector).forEach(el => {
    el.addEventListener('mouseenter', () => {
      gsap.to(el, { x: 4, duration: 0.2, ease: 'power2.out' })
    })
    el.addEventListener('mouseleave', () => {
      gsap.to(el, { x: 0, duration: 0.3, ease: 'elastic.out(1, 0.5)' })
    })
    el.addEventListener('touchstart', () => {
      gsap.to(el, { x: 2, duration: 0.15, ease: 'power2.out' })
    }, { passive: true })
  })
}

export function initHomeAnimations() {
  if (prefersReducedMotion()) return

  const mobile = isMobile()

  const heroTl = gsap.timeline({ defaults: { ease: 'power3.out' } })
  heroTl
    .from('.hero-label', { opacity: 0, y: mobile ? 10 : 16, duration: 0.5 })
    .from('.hero-headline', {
      opacity: 0,
      y: mobile ? 24 : 40,
      duration: mobile ? 0.6 : 0.8,
      clipPath: mobile ? 'none' : 'inset(0 0 100% 0)',
    }, '-=0.2')
    .from('.hero-sub', { opacity: 0, y: 16, duration: 0.5 }, '-=0.35')
    .from('.search-bar', { opacity: 0, y: 16, scale: 0.98, duration: 0.45 }, '-=0.25')
    .from('.stats-row .stat-item', { opacity: 0, y: 12, stagger: 0.08, duration: 0.35 }, '-=0.15')
    .from('.popular-row .pop-tag', { opacity: 0, scale: 0.92, stagger: 0.05, duration: 0.3 }, '-=0.15')

  if (document.querySelector('.hero-visuals .bento-container')) {
    gsap.from('.hero-visuals .bento-card', {
      opacity: 0,
      y: mobile ? 20 : 40,
      scale: mobile ? 0.98 : 0.97,
      rotationX: mobile ? 0 : 8,
      rotationY: mobile ? 0 : 10,
      stagger: 0.12,
      duration: mobile ? 0.55 : 0.85,
      ease: 'power3.out',
      delay: 0.15,
    })
  }

  gsap.to('.float-el-1', {
    y: mobile ? -12 : -25,
    x: mobile ? 8 : 20,
    rotation: mobile ? 0 : 360,
    duration: mobile ? 8 : 12,
    repeat: -1,
    yoyo: true,
    ease: 'sine.inOut',
  })

  gsap.to('.float-el-2', {
    y: mobile ? 10 : 20,
    x: mobile ? -8 : -15,
    rotation: mobile ? 0 : -180,
    duration: mobile ? 10 : 15,
    repeat: -1,
    yoyo: true,
    ease: 'sine.inOut',
  })

  gsap.from('.bento .bento-card', {
    scrollTrigger: { trigger: '.bento', start: 'top 85%' },
    opacity: 0,
    y: mobile ? 24 : 36,
    scale: 0.98,
    stagger: 0.1,
    duration: 0.65,
    ease: 'power3.out',
  })

  document.querySelectorAll('[data-count]').forEach(el => {
    const target = parseFloat(el.dataset.count)
    const suffix = el.dataset.suffix || ''
    const isFloat = el.dataset.float === 'true'

    ScrollTrigger.create({
      trigger: el,
      start: 'top 88%',
      once: true,
      onEnter() {
        gsap.to({ val: 0 }, {
          val: target,
          duration: 1.4,
          ease: 'power2.out',
          onUpdate() {
            el.textContent = isFloat
              ? this.targets()[0].val.toFixed(1) + suffix
              : Math.floor(this.targets()[0].val).toLocaleString() + suffix
          },
        })
      },
    })
  })

  gsap.from('.step-card', {
    scrollTrigger: { trigger: '.steps-grid', start: 'top 80%' },
    opacity: 0,
    y: mobile ? 20 : 0,
    x: mobile ? 0 : -24,
    stagger: 0.12,
    duration: 0.55,
    ease: 'power2.out',
  })

  bindHoverShift('.job-item')

  const strip = document.querySelector('.logos-strip')
  if (strip && !mobile) {
    const wrapper = strip.parentElement
    if (wrapper) wrapper.classList.add('logos-strip-wrap')
    const clone = strip.cloneNode(true)
    strip.parentNode.appendChild(clone)
    gsap.to([strip, clone], {
      x: `-=${strip.offsetWidth}`,
      repeat: -1,
      ease: 'none',
      duration: 22,
      modifiers: {
        x: gsap.utils.unitize(x => parseFloat(x) % strip.offsetWidth),
      },
    })
  } else if (strip && mobile) {
    gsap.from('.logo-pill', {
      scrollTrigger: { trigger: strip, start: 'top 90%' },
      opacity: 0,
      scale: 0.9,
      stagger: 0.06,
      duration: 0.4,
      ease: 'power2.out',
    })
  }

  gsap.utils.toArray('.fade-section').forEach(section => {
    gsap.from(section, {
      scrollTrigger: { trigger: section, start: 'top 88%' },
      opacity: 0,
      y: mobile ? 20 : 28,
      duration: 0.6,
      ease: 'power2.out',
    })
  })

  gsap.from('.testi-item', {
    scrollTrigger: { trigger: '.testimonial-strip', start: 'top 85%' },
    opacity: 0,
    y: 24,
    stagger: 0.12,
    duration: 0.55,
    ease: 'power2.out',
  })

  document.querySelectorAll('.map-pin').forEach(pin => {
    gsap.to(pin, {
      scale: 1.2,
      repeat: -1,
      yoyo: true,
      duration: 1.4,
      ease: 'sine.inOut',
    })
  })

  bindCardPress('.bento-card, .step-card, .pop-tag')
}

export function initPageAnimations() {
  if (prefersReducedMotion()) return

  const mobile = isMobile()

  gsap.utils.toArray('.fade-section').forEach(section => {
    gsap.from(section, {
      scrollTrigger: { trigger: section, start: 'top 90%' },
      opacity: 0,
      y: mobile ? 16 : 24,
      duration: 0.55,
      ease: 'power2.out',
    })
  })

  gsap.from('.job-card, .company-card, .dashboard-card', {
    scrollTrigger: { trigger: 'main', start: 'top 95%' },
    opacity: 0,
    y: 16,
    stagger: 0.06,
    duration: 0.5,
    ease: 'power2.out',
  })

  bindCardPress('.job-card, .company-card, .glass.rounded-lg, .glass.rounded-2xl, .glass.rounded-3xl')
  bindHoverShift('.job-item')
}
