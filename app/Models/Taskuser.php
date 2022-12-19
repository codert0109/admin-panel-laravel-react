<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taskuser extends Model
{
    use HasFactory;
    protected $table = 'taskuser';

    protected $fillable = [
        'task_id', 'user_id'
    ];

}
