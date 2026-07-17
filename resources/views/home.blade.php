@extends('layouts.app', ['page' => 'home'])

@section('title', 'JobsUG - Uganda\'s Job Marketplace')

@section('content')
<div class="page-wrap">
    @include('partials.hero', [
        'hero' => $viewModel->hero(),
        'stats' => $viewModel->platformStats(),
        'popularSearches' => $viewModel->popularSearches(),
    ])

    @include('partials.bento', [
        'jobs' => $viewModel->latestJobs(),
        'featuredCompanies' => $viewModel->featuredCompanies(),
        'categories' => $viewModel->categories(),
    ])

    @include('partials.how-it-works')

    <x-bento.stats-card />

    @include('partials.ai-career-tools')

    @include('partials.testimonials')

    @include('partials.companies-strip', [
        'companies' => $viewModel->trustedCompanies(),
    ])

</div>
@endsection
