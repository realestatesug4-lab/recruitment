@props(['number', 'icon', 'title', 'description'])

<div class="step-card glass rounded-xl sm:rounded-2xl p-5 sm:p-7 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl active:scale-[0.99]">
    <div class="step-number font-syne font-extrabold text-5xl text-forest/6 leading-none mb-4">{{ $number }}</div>
    <div class="step-icon-wrap w-11 h-11 rounded-xl bg-mint/12 flex items-center justify-center text-xl mb-3.5">{{ $icon }}</div>
    <div class="step-name font-syne font-bold text-deep mb-2">{{ $title }}</div>
    <div class="step-desc text-sm text-text-mid leading-relaxed">{{ $description }}</div>
</div>
