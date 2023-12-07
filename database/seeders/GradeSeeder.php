<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
        foreach (range(10, 12) as $grade) {
            foreach (range(1, 3) as $class) {
                $data[] = ['name' => "{$grade} IPA {$class}", 'created_at' => Carbon::now()];
            }
        }

        DB::table('grades')->insert($data);
    }
}
