<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CraneLinks') }}</title>

        <link rel="icon" type="image/png" href="{{ asset('images/cranelinks.png') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=Manrope:wght@200..800&display=swap" rel="stylesheet">

        @vite([
            'resources/css/app.css',
            'resources/js/app.js',
        ])

    </head>
    <body class="font-sans text-text-dark antialiased">
        <main class="flex min-h-screen flex-col px-4 py-8 sm:px-6 lg:px-8">
            <div class="mx-auto grid w-full max-w-6xl flex-1 items-center gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:gap-14">
                {{-- Left panel: brand + hero (desktop / tablet landscape only) --}}
                <section class="hidden lg:block">
                    <a href="{{ route('home') }}" class="group flex items-center gap-3">
                        <div class="h-11 w-11 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200 transition-transform duration-300 group-hover:scale-105">
                            <img
                                src="{{ asset('images/cranelinks.png') }}"
                                alt="CraneLinks Logo"
                                class="h-full w-full object-cover"
                            >
                        </div>
                        <div class="flex flex-col leading-none">
                            <span class="font-syne text-xl font-extrabold tracking-tight text-forest">
                                CraneLinks
                            </span>
                            <span class="text-xs uppercase tracking-[0.25em] text-gray-500">
                                One Click Away
                            </span>
                        </div>
                    </a>
                    <h1 class="mt-10 max-w-xl font-syne text-4xl font-bold leading-tight text-deep xl:text-5xl">
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

                {{-- Right panel: auth card (all screen sizes) --}}
                <section class="mx-auto w-full max-w-md">
                    {{-- Compact brand mark for mobile / tablet --}}
                    <div class="mb-6 flex items-center justify-center lg:hidden">
                        <a href="{{ route('home') }}" class="group flex items-center gap-3">
                            <div class="h-11 w-11 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200 transition-transform duration-300 group-hover:scale-105">
                                <img
                                    src="{{ asset('images/cranelinks.png') }}"
                                    alt="CraneLinks Logo"
                                    class="h-full w-full object-cover"
                                >
                            </div>
                            <div class="flex flex-col leading-none">
                                <span class="font-syne text-xl font-extrabold tracking-tight text-forest">
                                    CraneLinks
                                </span>
                                <span class="text-xs uppercase tracking-[0.25em] text-gray-500">
                                    One Click Away
                                </span>
                            </div>
                        </a>
                    </div>

                    <div class="glass rounded-lg p-5 shadow-xl sm:p-8">
                        {{ $slot }}
                    </div>
                </section>
            </div>
        </main>
    </body>
</html>
