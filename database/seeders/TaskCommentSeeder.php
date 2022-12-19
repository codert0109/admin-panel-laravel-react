<?php

namespace Database\Seeders;

use App\Models\TaskComment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        TaskComment::create(['creator_id' => 1, 'task_id' => 1, 'message' => 'comment message one', 'messageType' => 'text']);
        TaskComment::create(['creator_id' => 1, 'task_id' => 1, 'message' => 'comment message two', 'messageType' => 'text']);
    }
}
