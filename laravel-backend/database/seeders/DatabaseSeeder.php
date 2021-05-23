<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::factory()->count(2)->create();
        Auction::factory()->count(20)->create();

        $this->command->info('Dummy data has been created.');
    }
}
