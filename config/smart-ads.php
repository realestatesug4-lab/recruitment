<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'route' =>[
        'prefix' => '',
        'middleware' => 'web',
    ],

    /*
    |--------------------------------------------------------------------------
    | Ad Zones
    |--------------------------------------------------------------------------
    |
    | Zone definitions for the <x-ad-zone /> Blade component. Keys must match
    | the `slug` of the corresponding App\Domain\Advertising\Models\AdZone row.
    |
    | - slot:            layout placement marker (top, sidebar, inline, footer)
    | - revive_zone_id:  Revive Adserver zone the slot delivers from
    | - invocation_code: optional raw invocation tag override; when null the
    |                    component builds the standard Revive asyncjs tag
    |                    from revive_zone_id and services.revive.url
    |
    */
    'zones' => [
        'home_top' => [
            'slot' => 'top',
            'revive_zone_id' => null,
            'invocation_code' => null,
        ],
        'home_sidebar' => [
            'slot' => 'sidebar',
            'revive_zone_id' => null,
            'invocation_code' => null,
        ],
        'jobs_top' => [
            'slot' => 'top',
            'revive_zone_id' => null,
            'invocation_code' => null,
        ],
        'jobs_sidebar' => [
            'slot' => 'sidebar',
            'revive_zone_id' => null,
            'invocation_code' => null,
        ],
        'job_detail_top' => [
            'slot' => 'top',
            'revive_zone_id' => null,
            'invocation_code' => null,
        ],
        'job_detail_sidebar' => [
            'slot' => 'sidebar',
            'revive_zone_id' => null,
            'invocation_code' => null,
        ],
        'job_inline' => [
            'slot' => 'inline',
            'revive_zone_id' => null,
            'invocation_code' => null,
        ],
        'employer_banner' => [
            'slot' => 'top',
            'revive_zone_id' => null,
            'invocation_code' => null,
        ],
        'mobile_top' => [
            'slot' => 'top',
            'revive_zone_id' => null,
            'invocation_code' => null,
        ],
        'mobile_inline' => [
            'slot' => 'inline',
            'revive_zone_id' => null,
            'invocation_code' => null,
        ],
    ],
];
