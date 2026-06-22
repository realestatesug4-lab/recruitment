<?php

namespace App\Support;

class Navigation
{
    public static function main(): array
    {
        return [
            [
                'label' => 'Home',
                'route' => 'home'
            ],
            [
                'label' => 'Find Jobs',
                'route' => 'jobs.index',
            ],
            [
                'label' => 'Companies',
                'route' => 'companies.index',
            ],
            [
                'label' => 'About Us',
                'route' => 'about',
            ],
            [
                'label' => 'Resources',
                'route' => 'resources',
            ],
        ];
    }
}
