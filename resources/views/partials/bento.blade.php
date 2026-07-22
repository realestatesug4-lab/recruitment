<div class="bento fade-section grid grid-cols-12 gap-3 sm:gap-4 mt-8 sm:mt-12 px-4 sm:px-0">
    <div class="col-span-12 lg:col-span-7 lg:row-span-2">
        <x-bento.jobs-card :jobs="$jobs" />
    </div>

    <div class="col-span-12 sm:col-span-6 lg:col-span-5">
        <x-bento.company-card :company="$featuredCompanies[0]" />
    </div>

    <div class="col-span-12 sm:col-span-6 lg:col-span-5">
        <x-bento.company-card :company="$featuredCompanies[1]" />
    </div>

    <div class="col-span-12 lg:col-span-7">
        <x-bento.categories-card :categories="$categories" />
    </div>

    <div class="col-span-12 lg:col-span-5">
        <x-bento.employer-cta />
    </div>
</div>
