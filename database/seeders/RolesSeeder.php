<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'role_id' => '1',
            'name' => 'Админ',
            'slug' => 'admin',
        ]);
        DB::table('roles')->insert([
            'role_id' => '2',
            'name' => 'Редактор',
            'slug' => 'redaktor',
        ]);
        DB::table('roles')->insert([
            'role_id' => '0',
            'name' => 'Пользователь',
            'slug' => 'user',
        ]);
    }
}
