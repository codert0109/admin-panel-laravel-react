<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Task::create(['creator_id' => 1, 'column_id' => 1, 'name' => 'task one', 'description' => 'task one description', 'start_date' => '2022-12-1', 'end_date' => '2022-12-15']);
        Task::create(['creator_id' => 1, 'column_id' => 2, 'name' => 'task two', 'description' => 'task two description', 'start_date' => '2022-12-5', 'end_date' => '2022-12-17']);
    }
}
