<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Str;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('grades')->insert(array(
        array('name' => '10IPA1'),
        array('name' => '10IPA2'),
        array('name' => '10IPA3'),
        array('name' => '11IPA1'),
        array('name' => '11IPA2'),
        array('name' => '11IPA3'),

        ));
    }
}
