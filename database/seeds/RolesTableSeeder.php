<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void Returns nothing.
     */
    public function run()
    {
        Role::create(['name' => 'user']);
    }
}
