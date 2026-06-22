

import Alpine from 'alpinejs';
import searchBar from './alpine-components/search';
import jobFilters from './alpine-components/job-filters';

window.Alpine = Alpine;

Alpine.data('searchBar', searchBar);
Alpine.data('jobFilters', jobFilters);

Alpine.start();
