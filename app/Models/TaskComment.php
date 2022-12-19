<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    use HasFactory;

    protected $table = "taskcomments";

    protected $fillable = [
        'creator_id',
        'task_id',
        'avatar',
        'message',
        'messageType'
    ];

    public function creator() 
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
