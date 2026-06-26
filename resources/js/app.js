

import Alpine from 'alpinejs';
import searchBar from './alpine-components/search';
import jobFilters from './alpine-components/job-filters';
import dashboard from './alpine-components/dashboard';

window.Alpine = Alpine;

Alpine.data('searchBar', searchBar);
Alpine.data('jobFilters', jobFilters);
Alpine.data('dashboard', dashboard);

Alpine.start();
