<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!DB::table('categories')->first()) {
            DB::table('categories')->insert([
                ['name' => '解熱鎮痛薬'],
                ['name' => '風邪薬'],
                ['name' => '皮膚薬'],
                ['name' => '目薬'],
                ['name' => '胃腸薬'],
                ['name' => 'アレルギー薬'],
                ['name' => '整腸薬'],
                ['name' => '漢方薬'],
                ['name' => 'その他'],
            ]);
        }
    }
}
