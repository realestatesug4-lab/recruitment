<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'CraneLinks'))</title>

        {{-- SEO Meta Tags --}}
        <meta name="description" content="@yield('meta_description', 'CraneLinks — Uganda\'s fast, low-data way to find jobs, companies, and career opportunities. Browse verified employers, apply with AI-powered tools, and track your applications.')">
        <link rel="canonical" href="@yield('canonical', url()->current())">
        <meta name="robots" content="@yield('robots', 'index, follow')">

        {{-- Open Graph --}}
        <meta property="og:type" content="@yield('og_type', 'website')">
        <meta property="og:title" content="@yield('og_title', config('app.name', 'CraneLinks'))">
        <meta property="og:description" content="@yield('og_description', 'Uganda\'s fast, low-data way to find jobs, companies, and career opportunities.')">
        <meta property="og:url" content="@yield('og_url', url()->current())">
        <meta property="og:site_name" content="CraneLinks">
        <meta property="og:image" content="@yield('og_image', asset('images/cranelinks.png'))">
        <meta property="og:locale" content="en_UG">

        {{-- Twitter Card --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="@yield('twitter_title', config('app.name', 'CraneLinks'))">
        <meta name="twitter:description" content="@yield('twitter_description', 'Uganda\'s fast, low-data way to find jobs, companies, and career opportunities.')">
        <meta name="twitter:image" content="@yield('twitter_image', asset('images/cranelinks.png'))">

        {{-- Schema.org Organization + WebSite JSON-LD --}}
        <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@graph": [
                {
                    "@type": "Organization",
                    "@id": "{{ url('/') }}/#organization",
                    "name": "CraneLinks",
                    "url": "{{ url('/') }}",
                    "logo": "{{ asset('images/cranelinks.png') }}",
                    "description": "Uganda's fast, low-data recruitment platform connecting job seekers with verified employers.",
                    "address": {
                        "@type": "PostalAddress",
                        "addressCountry": "UG",
                        "addressLocality": "Kampala"
                    }
                },
                {
                    "@type": "WebSite",
                    "@id": "{{ url('/') }}/#website",
                    "url": "{{ url('/') }}",
                    "name": "CraneLinks",
                    "publisher": { "@id": "{{ url('/') }}/#organization" },
                    "potentialAction": {
                        "@type": "SearchAction",
                        "target": "{{ url('/jobs') }}?q={search_term_string}",
                        "query-input": "required name=search_term_string"
                    }
                }
            ]
        }
        </script>

        <link rel="icon" type="image/png" href="{{ asset('images/cranelinks.png') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=Manrope:wght@200..800&display=swap" rel="stylesheet">

        @vite([
            'resources/css/app.css',
            'resources/js/app.js',
        ])

        <script src="{{ asset('vendor/smart-ads/js/smart-banner.js') }}" defer></script>

        @stack('head')
    </head>
    <body class="font-sans antialiased text-text-dark" data-page="{{ $page ?? 'default' }}">
        <div class="min-h-screen">
            @isset($slot)
                @include('layouts.navigation')
            @else
                @include('partials.nav')
            @endisset

            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>

            <footer>
                @include('partials.footer')
            </footer>
        </div>

        @stack('scripts')
    </body>
</html>
