<?php

use Illuminate\Database\Seeder;
use App\Models\Cleaner;

class CleanersTableSeeder extends Seeder
{
    public function run()
    {
        factory(Cleaner::class, 5)->create();
    }
}
