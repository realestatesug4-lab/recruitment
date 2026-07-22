import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import searchBar from './alpine-components/search';
import jobFilters from './alpine-components/job-filters';
import dashboard from './alpine-components/dashboard';
import { initHomeAnimations, initPageAnimations } from './gsap-animations';

Alpine.plugin(collapse);

window.Alpine = Alpine;

Alpine.data('searchBar', searchBar);
Alpine.data('jobFilters', jobFilters);
Alpine.data('dashboard', dashboard);

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
  const page = document.body.dataset.page;

  requestAnimationFrame(() => {
    if (page === 'home') {
      initHomeAnimations();
    } else {
      initPageAnimations();
    }
  });
});
