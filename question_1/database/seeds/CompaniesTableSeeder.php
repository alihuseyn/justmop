<?php

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompaniesTableSeeder extends Seeder
{
    public function run()
    {
        factory(Company::class, 5)->create();
    }
}
