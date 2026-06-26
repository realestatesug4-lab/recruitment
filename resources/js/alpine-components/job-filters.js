export default function jobFilters() {
  return {
    // Filter state
    filters: {
      function:   [],
      industry:   [],
      location:   '',
      experience: '',
      type:       [],
      salaryMin:  null,
      salaryMax:  null,
      remote:     false,
    },
    sort: 'relevance',
    loading: false,
    jobs: [],
    total: 0,
    page: 1,

    // Sync with URL on init
    init() {
      const params = new URLSearchParams(window.location.search);
      if (params.get('location')) this.filters.location = params.get('location');
      if (params.get('sort'))     this.sort = params.get('sort');
      this.fetchJobs();
    },

    async fetchJobs() {
      this.loading = true;
      const params = new URLSearchParams();

      if (this.filters.location) {
        params.append('location', this.filters.location);
      }

      if (this.filters.salaryMax !== null && this.filters.salaryMax !== '') {
        params.append('salaryMax', this.filters.salaryMax);
      }

      if (this.filters.salaryMin !== null && this.filters.salaryMin !== '') {
        params.append('salaryMin', this.filters.salaryMin);
      }

      if (this.filters.remote) {
        params.append('remote', 'true');
      }

      this.filters.function.forEach((value) => {
        if (value) params.append('function[]', value);
      });

      this.filters.type.forEach((value) => {
        if (value) params.append('type[]', value);
      });

      params.append('sort', this.sort);
      params.append('page', this.page);

      // Push to URL without reload
      history.pushState({}, '', `?${params}`);
      const res = await fetch(`/api/jobs?${params}`);
      const data = await res.json();
      this.jobs  = data.data;
      this.total = data.meta.total;
      this.loading = false;
    },

    toggleFilter(key, value) {
      const arr = this.filters[key];
      const idx = arr.indexOf(value);
      if (idx > -1) arr.splice(idx, 1);
      else arr.push(value);
      this.page = 1;
      this.fetchJobs();
    },

    clearAll() {
      this.filters = { function:[], industry:[], location:'', experience:'', type:[], salaryMin:null, salaryMax:null, remote:false };
      this.page = 1;
      this.fetchJobs();
    }
  }
}
