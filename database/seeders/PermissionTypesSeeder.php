<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            'permission_name' => 'admin',
            'permission_id' => 1,
        ]);
        DB::table('permissions')->insert([
            'permission_name' => 'editor',
            'permission_id' => 2,
        ]);
    }
}
