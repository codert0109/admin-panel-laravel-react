<?php

namespace Database\Seeders;

use App\Models\Taskuser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Taskuser::create(['task_id' => 1, 'user_id' => 1]);
        Taskuser::create(['task_id' => 1, 'user_id' => 2]);
    }
}
