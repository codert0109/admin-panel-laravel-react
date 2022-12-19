<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskColumn;
use App\Models\TaskColumnOrder;

class TaskController extends Controller
{
    //
    public function getAll()
    {
        $tasks = Task::where('id', '>', 0)->with('assignee')->with('comments')->with('creator')->get();
        $taskColumns = TaskColumn::where('id', '>',  0)->with('tasks')->get();
        $taskColumnOrder = TaskColumnOrder::first()->column_order;
        $taskColumnOrder = explode(",", $taskColumnOrder);
        return ['cards' => $tasks, 'columns' => $taskColumns, 'columnOrder' => $taskColumnOrder];
    }

    public function createColumn(Request $request)
    {
        $new = TaskColumn::create(['name' => $request->name]);
        $taskColumnOrder = TaskColumnOrder::first()->column_order;
        $taskColumnOrder .= "," . $new->id;
        $update = TaskColumnOrder::first()->update(['column_order' => $taskColumnOrder]);
        return $new;
    }

    public function updateColumn(Request $request)
    {
        $new = TaskColumn::where('id', '=', $request->columnId)->update(['name' => $request->updateColumn['name']]);
        return $new;
    }

    public function deleteColumn(Request $request)
    {
        $columnId = $request->columnId;
        $taskColumnOrder = TaskColumnOrder::first()->column_order;
        $taskColumnOrder = explode(",", $taskColumnOrder);
        $newOrder = [];
        foreach ($taskColumnOrder as $column) {
            if ($column != $columnId) {
                array_push($newOrder, $column);
            }
        }
        $newOrder = implode(",", $newOrder);
        $update = TaskColumnOrder::first()->update(['column_order' => $newOrder]);
        return TaskColumn::where('id', '=', $request->columnId)->delete();
    }

    public function createTask(Request $request)
    {
        $new = Task::create(['name' => $request->card['name'], 'creator_id' => $request->creator_id, 'column_id' => $request->column_id]);
        return $new;
    }
}
