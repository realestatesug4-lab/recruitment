@extends('layouts.dashboard')

@section('title', 'AI Career Tools — CraneLinks')
@section('page_title', 'AI Career Tools')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8" x-data="aiTools()">
    <div class="mb-8">
        <div class="inline-flex items-center gap-2 rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" /></svg>
            AI Powered
        </div>
        <h1 class="mt-4 text-2xl font-bold text-gray-900">AI Career Tools</h1>
        <p class="mt-1 text-sm text-gray-500">Leverage AI to write cover letters, practise interviews, and benchmark your salary.</p>
    </div>

    @if(!$profile)
        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-5">
            <p class="text-sm font-semibold text-amber-800">Complete your profile first</p>
            <p class="mt-1 text-sm text-amber-600">AI tools work best when we know your skills and experience.</p>
            <a href="{{ route('seeker.profile.create') }}" class="mt-3 inline-flex rounded-lg bg-forest px-4 py-2 text-sm font-semibold text-white hover:bg-sage">Create profile</a>
        </div>
    @endif

    {{-- Tabs --}}
    <div class="mb-6 flex flex-wrap gap-2 border-b border-gray-200">
        <button @click="tab = 'cover-letter'" :class="tab === 'cover-letter' ? 'border-forest text-forest' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="border-b-2 px-4 py-3 text-sm font-semibold transition">Cover Letter</button>
        <button @click="tab = 'interview'" :class="tab === 'interview' ? 'border-forest text-forest' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="border-b-2 px-4 py-3 text-sm font-semibold transition">Interview Coach</button>
        <button @click="tab = 'salary'" :class="tab === 'salary' ? 'border-forest text-forest' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="border-b-2 px-4 py-3 text-sm font-semibold transition">Salary Coach</button>
        <button @click="tab = 'cv'" :class="tab === 'cv' ? 'border-forest text-forest' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="border-b-2 px-4 py-3 text-sm font-semibold transition">CV Generator</button>
    </div>

    {{-- Cover Letter --}}
    <div x-show="tab === 'cover-letter'" x-transition class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900">Generate a cover letter</h2>
            <p class="mt-1 text-sm text-gray-500">Provide the job details and we will craft a tailored cover letter.</p>
            <div class="mt-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Job title</label>
                    <input x-model="coverForm.job_title" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm" placeholder="e.g. Senior Frontend Developer">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Company name</label>
                    <input x-model="coverForm.company_name" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm" placeholder="e.g. MTN Uganda">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Job description</label>
                    <textarea x-model="coverForm.job_description" rows="6" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm" placeholder="Paste the job description or key requirements..."></textarea>
                </div>
                <button @click="generate('cover-letter', coverForm)" :disabled="loading"
                        class="w-full rounded-lg bg-forest py-2.5 text-sm font-semibold text-white hover:bg-sage disabled:opacity-50 transition">
                    <span x-show="!loading">Generate cover letter</span>
                    <span x-show="loading">Generating...</span>
                </button>
            </div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900">Result</h3>
            <div x-show="!result" class="mt-4 flex h-64 items-center justify-center rounded-lg border border-dashed border-gray-200 text-sm text-gray-400">
                Your generated cover letter will appear here.
            </div>
            <div x-show="result" class="mt-4 whitespace-pre-line text-sm leading-relaxed text-gray-700 max-h-96 overflow-y-auto" x-text="result"></div>
            <button x-show="result" @click="copyResult()" class="mt-4 rounded-lg border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">Copy to clipboard</button>
        </div>
    </div>

    {{-- Interview Coach --}}
    <div x-show="tab === 'interview'" x-transition class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900">Interview coach</h2>
            <p class="mt-1 text-sm text-gray-500">Practise with AI-powered interview guidance using the STAR method.</p>
            <div class="mt-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Target role</label>
                    <input x-model="interviewForm.role" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm" placeholder="e.g. Marketing Manager">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Interview question</label>
                    <textarea x-model="interviewForm.question" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm" placeholder="e.g. Tell me about a time you led a team through a difficult project."></textarea>
                </div>
                <button @click="generate('interview', interviewForm)" :disabled="loading"
                        class="w-full rounded-lg bg-forest py-2.5 text-sm font-semibold text-white hover:bg-sage disabled:opacity-50 transition">
                    <span x-show="!loading">Get coaching</span>
                    <span x-show="loading">Thinking...</span>
                </button>
            </div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900">AI coaching response</h3>
            <div x-show="!result" class="mt-4 flex h-64 items-center justify-center rounded-lg border border-dashed border-gray-200 text-sm text-gray-400">
                Coaching guidance will appear here.
            </div>
            <div x-show="result" class="mt-4 whitespace-pre-line text-sm leading-relaxed text-gray-700 max-h-96 overflow-y-auto" x-text="result"></div>
            <button x-show="result" @click="copyResult()" class="mt-4 rounded-lg border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">Copy</button>
        </div>
    </div>

    {{-- Salary Coach --}}
    <div x-show="tab === 'salary'" x-transition class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900">Salary benchmarking</h2>
            <p class="mt-1 text-sm text-gray-500">Get market-rate insights and negotiation strategies for your role.</p>
            <div class="mt-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Role</label>
                    <input x-model="salaryForm.role" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm" placeholder="e.g. Data Analyst">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Location</label>
                    <input x-model="salaryForm.location" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm" placeholder="e.g. Kampala">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Years of experience</label>
                    <input x-model="salaryForm.years_experience" type="number" min="0" max="50" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm" placeholder="5">
                </div>
                <button @click="generate('salary', salaryForm)" :disabled="loading"
                        class="w-full rounded-lg bg-forest py-2.5 text-sm font-semibold text-white hover:bg-sage disabled:opacity-50 transition">
                    <span x-show="!loading">Get salary analysis</span>
                    <span x-show="loading">Analysing...</span>
                </button>
            </div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900">Salary analysis</h3>
            <div x-show="!result" class="mt-4 flex h-64 items-center justify-center rounded-lg border border-dashed border-gray-200 text-sm text-gray-400">
                Salary analysis will appear here.
            </div>
            <div x-show="result" class="mt-4 whitespace-pre-line text-sm leading-relaxed text-gray-700 max-h-96 overflow-y-auto" x-text="result"></div>
            <button x-show="result" @click="copyResult()" class="mt-4 rounded-lg border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">Copy</button>
        </div>
    </div>

    {{-- CV Generator --}}
    <div x-show="tab === 'cv'" x-transition class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900">ATS-optimised CV</h2>
            <p class="mt-1 text-sm text-gray-500">Generate a tailored CV matched to a specific job description.</p>
            <div class="mt-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Job description</label>
                    <textarea x-model="cvForm.job_description" rows="8" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm" placeholder="Paste the full job description..."></textarea>
                </div>
                <button @click="generate('cv', cvForm)" :disabled="loading"
                        class="w-full rounded-lg bg-forest py-2.5 text-sm font-semibold text-white hover:bg-sage disabled:opacity-50 transition">
                    <span x-show="!loading">Generate CV</span>
                    <span x-show="loading">Generating...</span>
                </button>
            </div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-900">Generated CV</h3>
            <div x-show="!result" class="mt-4 flex h-64 items-center justify-center rounded-lg border border-dashed border-gray-200 text-sm text-gray-400">
                Your AI-generated CV will appear here.
            </div>
            <div x-show="result" class="mt-4 whitespace-pre-line text-sm leading-relaxed text-gray-700 max-h-96 overflow-y-auto" x-text="result"></div>
            <button x-show="result" @click="copyResult()" class="mt-4 rounded-lg border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">Copy</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function aiTools() {
    return {
        tab: 'cover-letter',
        loading: false,
        result: '',
        coverForm: { job_title: '', company_name: '', job_description: '' },
        interviewForm: { role: '', question: '' },
        salaryForm: { role: '', location: 'Kampala', years_experience: 3 },
        cvForm: { job_description: '' },

        async generate(endpoint, form) {
            this.loading = true;
            this.result = '';
            try {
                const res = await fetch(`/seeker/ai-tools/${endpoint}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(form),
                });
                const data = await res.json();
                this.result = data.content || 'No result generated. Please try again.';
            } catch (e) {
                this.result = 'An error occurred. Please try again.';
            } finally {
                this.loading = false;
            }
        },

        copyResult() {
            navigator.clipboard.writeText(this.result).then(() => {
                // brief visual feedback
            });
        },
    };
}
</script>
@endpush
@endsection
