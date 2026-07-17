export default function searchBar() {
  return {
    query: '',
    location: 'Kampala, UG',
    suggestions: [],
    open: false,

    async fetchSuggestions() {
      if (this.query.length < 2) {
        this.suggestions = [];
        this.open = false;
        return;
      }

      try {
        const res = await fetch(`/api/jobs/suggest?q=${encodeURIComponent(this.query)}`);
        this.suggestions = await res.json();
        this.open = this.suggestions.length > 0;
      } catch (error) {
        this.suggestions = [];
        this.open = false;
      }
    },

    selectSuggestion(s) {
      this.query = s.title;
      this.open = false;
      this.submit();
    },

    submit() {
      const url = new URL('/jobs', window.location.origin);
      if (this.query.trim()) {
        url.searchParams.set('q', this.query.trim());
      }
      if (this.location.trim()) {
        url.searchParams.set('location', this.location.trim());
      }
      window.location.href = url.toString();
    }
  }
}
