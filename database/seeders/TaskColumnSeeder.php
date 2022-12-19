<?php

namespace Database\Seeders;

use App\Models\TaskColumn;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskColumnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        TaskColumn::create(['name' => 'Backlog']);
        TaskColumn::create(['name' => 'Progress']);
        TaskColumn::create(['name' => 'Done']);
    }
}
