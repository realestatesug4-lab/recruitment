<?php

namespace Database\Seeders;

use App\Domain\Companies\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [

            [
            'name'=>'MTN Uganda',
            'industry'=>'Telecommunications'
            ],

            [
            'name'=>'Airtel Uganda',
            'industry'=>'Telecommunications'
            ],

            [
            'name'=>'Stanbic Bank Uganda',
            'industry'=>'Banking'
            ],

            [
            'name'=>'Centenary Bank',
            'industry'=>'Banking'
            ],

            [
            'name'=>'Dfcu Bank',
            'industry'=>'Banking'
            ],

            [
            'name'=>'NSSF Uganda',
            'industry'=>'Government'
            ],

            [
            'name'=>'Uganda Revenue Authority',
            'industry'=>'Government'
            ],

            [
            'name'=>'SafeBoda',
            'industry'=>'Technology'
            ],

            [
            'name'=>'Movit Products',
            'industry'=>'Manufacturing'
            ],

            [
            'name'=>'Nation Media Group Uganda',
            'industry'=>'Media'
            ],

            [
            'name'=>'Monitor Publications',
            'industry'=>'Media'
            ],

        ];

        foreach ($companies as $company)
        {
            Company::create([
                'name' => $company['name'],

                'slug' => Str::slug($company['name']),

                'industry' => $company['industry'],

                // 'website' => $company['website'],

                // 'location' => $company['location'],

                // 'description' => $company['description'],
            ]);
        }
    }
}
