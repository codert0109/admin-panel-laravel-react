<?php

namespace Database\Seeders;

use App\Models\TaskColumnOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskColumnOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        TaskColumnOrder::create(['column_order' => "1,2,3"]);
    }
}
