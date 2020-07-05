<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void Returns nothing.
     */
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
        ]);
    }
}
