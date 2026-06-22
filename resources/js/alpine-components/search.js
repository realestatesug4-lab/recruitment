export default function searchBar() {
  return {
    query: '',
    location: 'Kampala, UG',
    suggestions: [],
    open: false,

    async fetchSuggestions() {
      if (this.query.length < 2) { this.suggestions = []; return; }
      const res = await fetch(`/api/jobs/suggest?q=${encodeURIComponent(this.query)}`);
      this.suggestions = await res.json();
      this.open = true;
    },

    selectSuggestion(s) {
      this.query = s.title;
      this.open = false;
      this.submit();
    },

    submit() {
      const url = new URL('/jobs', window.location.origin);
      url.searchParams.set('q', this.query);
      url.searchParams.set('location', this.location);
      window.location.href = url.toString();
    }
  }
}
