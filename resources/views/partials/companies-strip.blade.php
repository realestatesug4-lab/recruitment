<div class="text-center mb-4">
    <span class="text-xs text-text-light uppercase tracking-wider font-semibold">Trusted by Uganda's leading employers</span>
</div>
<div class="logos-strip flex flex-wrap justify-center gap-2 mb-20">
    @foreach($companies as $company)
    <div class="logo-pill bg-white/55 border border-white/70 rounded-full px-5 py-2.5 text-sm font-semibold text-text-mid flex items-center gap-1.5 backdrop-blur-sm transition-all duration-200 cursor-pointer hover:bg-white/80 hover:text-forest hover:-translate-y-0.5 hover:shadow-md">
        <span class="logo-pill-dot w-1.5 h-1.5 rounded-full" style="background: {{ $company['dot'] }}"></span>
        {{ $company['name'] }}
    </div>
    @endforeach
</div>
