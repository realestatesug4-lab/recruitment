import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

const interactiveEvents = new WeakSet()

function prefersReducedMotion() {
  return window.matchMedia('(prefers-reduced-motion: reduce)').matches
}

function isMobile() {
  return window.matchMedia('(max-width: 767px)').matches
}

function select(selector) {
  if (Array.isArray(selector)) return selector
  if (selector instanceof Element) return [selector]
  return Array.from(document.querySelectorAll(selector))
}

function showImmediately(selector) {
  select(selector).forEach(el => {
    gsap.killTweensOf(el)
    el.style.opacity = '1'
    el.style.transform = 'none'
    el.style.visibility = 'visible'
  })
}

function reveal(selector, options = {}) {
  const items = select(selector)
  if (!items.length) return

  const mobile = isMobile()
  const from = {
    autoAlpha: 0,
    y: mobile ? 14 : 22,
    scale: 1,
    ...options.from,
  }

  const to = {
    autoAlpha: 1,
    y: 0,
    scale: 1,
    duration: mobile ? 0.42 : 0.56,
    stagger: mobile ? 0.045 : 0.075,
    ease: 'power2.out',
    overwrite: 'auto',
    clearProps: 'opacity,visibility,transform',
    ...options.to,
  }

  if (options.trigger) {
    to.scrollTrigger = {
      trigger: options.trigger,
      start: mobile ? 'top 92%' : 'top 86%',
      once: true,
      invalidateOnRefresh: true,
      ...options.scrollTrigger,
    }
  }

  gsap.fromTo(items, from, to)
}

function bindPress(selector) {
  select(selector).forEach(el => {
    if (interactiveEvents.has(el)) return
    interactiveEvents.add(el)

    const press = () => gsap.to(el, { scale: 0.985, duration: 0.12, ease: 'power2.out', overwrite: 'auto' })
    const release = () => gsap.to(el, { scale: 1, duration: 0.18, ease: 'power2.out', overwrite: 'auto' })

    el.addEventListener('pointerdown', press, { passive: true })
    el.addEventListener('pointerup', release, { passive: true })
    el.addEventListener('pointercancel', release, { passive: true })
    el.addEventListener('mouseleave', release)
  })
}

function bindDesktopHover(selector) {
  if (isMobile()) return

  select(selector).forEach(el => {
    if (el.dataset.kineticHover === 'true') return
    el.dataset.kineticHover = 'true'

    el.addEventListener('mouseenter', () => {
      gsap.to(el, { y: -3, duration: 0.18, ease: 'power2.out', overwrite: 'auto' })
    })

    el.addEventListener('mouseleave', () => {
      gsap.to(el, { y: 0, duration: 0.2, ease: 'power2.out', overwrite: 'auto' })
    })
  })
}

function initCounters() {
  select('[data-count]').forEach(el => {
    const target = Number.parseFloat(el.dataset.count)
    if (!Number.isFinite(target) || el.dataset.counted === 'true') return

    el.dataset.counted = 'true'
    const suffix = el.dataset.suffix || ''
    const isFloat = el.dataset.float === 'true'

    ScrollTrigger.create({
      trigger: el,
      start: 'top 92%',
      once: true,
      onEnter() {
        gsap.fromTo({ value: 0 }, { value: 0 }, {
          value: target,
          duration: 1,
          ease: 'power2.out',
          onUpdate() {
            const value = this.targets()[0].value
            el.textContent = isFloat
              ? `${value.toFixed(1)}${suffix}`
              : `${Math.floor(value).toLocaleString()}${suffix}`
          },
        })
      },
    })
  })
}

function initResolverDemo() {
  const timer = document.querySelector('#resolver-timer')
  const query = document.querySelector('.resolver-query')
  const title = document.querySelector('.resolver-title')
  const meta = document.querySelector('.resolver-meta')

  window.clearInterval(window.quicklinksTimerInterval)
  window.clearInterval(window.quicklinksResolverInterval)

  if (timer) {
    window.quicklinksTimerInterval = window.setInterval(() => {
      timer.textContent = `${(0.28 + Math.random() * 0.22).toFixed(2)}s`
    }, 2600)
  }

  if (!query || !title || !meta) return

  const items = [
    ['accountant jobs in Kampala', 'Senior Accountant - Nakawa', 'Fintech Co. | UGX 1.8M-2.4M'],
    ['boda service near me', 'Verified Boda Stage - Ntinda', 'Open now | WhatsApp contact'],
    ['school shoes in stock', 'Kampala Shoe Hub - Owino', 'Low-data catalog | WhatsApp seller'],
  ]

  let index = 0
  window.quicklinksResolverInterval = window.setInterval(() => {
    index = (index + 1) % items.length
    const nodes = [query, title, meta]

    gsap.to(nodes, {
      autoAlpha: 0,
      y: -5,
      duration: 0.16,
      ease: 'power2.in',
      overwrite: 'auto',
      onComplete() {
        query.textContent = items[index][0]
        title.textContent = items[index][1]
        meta.textContent = items[index][2]
        gsap.fromTo(nodes, { autoAlpha: 0, y: 6 }, { autoAlpha: 1, y: 0, duration: 0.24, stagger: 0.025, ease: 'power2.out', overwrite: 'auto' })
      },
    })
  }, 3200)
}

function initLogoStrip() {
  const strip = document.querySelector('.logos-strip')
  if (!strip || isMobile() || strip.dataset.marqueeReady === 'true') return

  strip.dataset.marqueeReady = 'true'
  const wrapper = strip.parentElement
  if (wrapper) wrapper.classList.add('logos-strip-wrap')

  const clone = strip.cloneNode(true)
  clone.setAttribute('aria-hidden', 'true')
  strip.parentNode.appendChild(clone)

  requestAnimationFrame(() => {
    const width = strip.offsetWidth
    if (!width) return

    gsap.to([strip, clone], {
      x: `-=${width}`,
      repeat: -1,
      ease: 'none',
      duration: 24,
      modifiers: {
        x: gsap.utils.unitize(x => parseFloat(x) % width),
      },
    })
  })
}

export function initHomeAnimations() {
  ScrollTrigger.getAll().forEach(trigger => trigger.kill())

  if (prefersReducedMotion()) {
    showImmediately('.hero-label, .hero-headline, .hero-sub, .search-bar, .stat-item, .pop-tag, .resolver-stage, .resolver-card, .bento-card, .step-card, .fade-section, .testi-item, .job-card, .company-card, .dashboard-card')
    return
  }

  const mobile = isMobile()

  gsap.timeline({ defaults: { ease: 'power2.out', overwrite: 'auto' } })
    .fromTo('.hero-label', { autoAlpha: 0, y: 12 }, { autoAlpha: 1, y: 0, duration: 0.38 })
    .fromTo('.hero-headline', { autoAlpha: 0, y: mobile ? 18 : 28 }, { autoAlpha: 1, y: 0, duration: mobile ? 0.48 : 0.62 }, '-=0.12')
    .fromTo('.hero-sub', { autoAlpha: 0, y: 12 }, { autoAlpha: 1, y: 0, duration: 0.4 }, '-=0.24')
    .fromTo('.search-bar', { autoAlpha: 0, y: 12, scale: 0.985 }, { autoAlpha: 1, y: 0, scale: 1, duration: 0.38 }, '-=0.18')
    .fromTo('.resolve-line, .popular-row .pop-tag', { autoAlpha: 0, y: 8 }, { autoAlpha: 1, y: 0, duration: 0.28, stagger: 0.035 }, '-=0.08')
    .fromTo('.resolver-stage', { autoAlpha: 0, y: 16 }, { autoAlpha: 1, y: 0, duration: 0.42 }, '-=0.02')
    .fromTo('.resolver-card', { autoAlpha: 0, y: mobile ? 12 : 18 }, { autoAlpha: 1, y: 0, duration: 0.36, stagger: 0.08 }, '-=0.16')
    .fromTo('.hero .stat-item', { autoAlpha: 0, y: 10 }, { autoAlpha: 1, y: 0, duration: 0.32, stagger: 0.06 }, '-=0.12')

  reveal('.bento .bento-card', { trigger: '.bento', from: { y: mobile ? 14 : 24, scale: 0.99 } })
  reveal('.step-card', { trigger: '.steps-grid', from: { y: mobile ? 14 : 22 } })
  select('.fade-section').forEach(section => {
    reveal([section], { trigger: section, from: { y: mobile ? 12 : 20 }, to: { stagger: 0 } })
  })
  reveal('.testi-item', { trigger: '.testimonial-strip', from: { y: mobile ? 12 : 20 } })
  reveal('.job-card, .company-card, .dashboard-card', { trigger: 'main', from: { y: 14 } })

  initCounters()
  initResolverDemo()
  initLogoStrip()
  bindPress('.bento-card, .step-card, .pop-tag, .job-card, .company-card')
  bindDesktopHover('.job-item, .cat-item, .company-card')

  window.setTimeout(() => ScrollTrigger.refresh(), 160)
}

export function initPageAnimations() {
  ScrollTrigger.getAll().forEach(trigger => trigger.kill())

  if (prefersReducedMotion()) {
    showImmediately('.fade-section, .job-card, .company-card, .dashboard-card')
    return
  }

  select('.fade-section').forEach(section => {
    reveal([section], { trigger: section, from: { y: isMobile() ? 12 : 20 }, to: { stagger: 0 } })
  })
  reveal('.job-card, .company-card, .dashboard-card', { trigger: 'main', from: { y: 14 } })

  bindPress('.job-card, .company-card, .dashboard-card, .glass.rounded-lg, .glass.rounded-2xl, .glass.rounded-3xl')
  bindDesktopHover('.job-item')

  window.setTimeout(() => ScrollTrigger.refresh(), 160)
}
