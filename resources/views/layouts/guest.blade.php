<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'JobsUG') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">

        @vite([
            'resources/css/app.css',
            'resources/js/app.js',
        ])

    </head>
    <body class="font-sans text-text-dark antialiased">
        <main class="min-h-screen px-6 py-8">
            <div class="mx-auto grid min-h-[calc(100vh-4rem)] max-w-6xl items-center gap-8 lg:grid-cols-[0.9fr_1.1fr]">
                <section class="hidden lg:block">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                        <x-application-logo class="h-12 w-12 text-forest" />
                        <span class="font-syne text-2xl font-bold text-deep">JobsUG</span>
                    </a>
                    <h1 class="mt-10 max-w-xl font-syne text-5xl font-bold leading-tight text-deep">
                        Find the right work, or the right hire, faster.
                    </h1>
                    <p class="mt-5 max-w-lg text-base leading-7 text-text-mid">
                        Sign in to manage applications, saved jobs, hiring dashboards, and company profiles in one focused workspace.
                    </p>
                    <div class="mt-8 grid max-w-md grid-cols-3 gap-3">
                        <div class="glass rounded-lg p-4">
                            <div class="font-syne text-2xl font-bold text-deep">Live</div>
                            <div class="mt-1 text-xs font-semibold uppercase tracking-wide text-text-light">Live jobs</div>
                        </div>
                        <div class="glass rounded-lg p-4">
                            <div class="font-syne text-2xl font-bold text-deep">Verified</div>
                            <div class="mt-1 text-xs font-semibold uppercase tracking-wide text-text-light">Companies</div>
                        </div>
                        <div class="glass rounded-lg p-4">
                            <div class="font-syne text-2xl font-bold text-deep">UG</div>
                            <div class="mt-1 text-xs font-semibold uppercase tracking-wide text-text-light">Market</div>
                        </div>
                    </div>
                </section>

                <section class="mx-auto w-full max-w-md">
                    <div class="mb-6 flex items-center justify-center lg:hidden">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                            <x-application-logo class="h-10 w-10 text-forest" />
                            <span class="font-syne text-xl font-bold text-deep">JobsUG</span>
                        </a>
                    </div>

                    <div class="glass rounded-lg p-6 shadow-xl sm:p-8">
                        {{ $slot }}
                    </div>
                </section>
            </div>
        </main>
    </body>
</html>
