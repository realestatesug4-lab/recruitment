<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Schema;
use Illuminate\View\Component;
use _5balloons\LaravelSmartAds\Models\SmartAd;

class SmartAdComponent extends Component
{
    public string $slug;

    public function __construct(string $slug)
    {
        $this->slug = $slug;
    }

    public function render()
    {
        try {
            if (! Schema::hasTable('smart_ads')) {
                return '';
            }

            $smartAd = SmartAd::query()
                ->where('slug', $this->slug)
                ->first();

            return view('smart-ads::components.smart-ad-component', compact('smartAd'));
        } catch (\Throwable $e) {
            report($e);

            return '';
        }
    }
}
