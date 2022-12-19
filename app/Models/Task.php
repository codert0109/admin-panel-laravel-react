<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'column_id',
        'name',
        'start_date',
        'end_date',
        'description',
        'completed',
    ];

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function assignee()
    {
        return $this->belongsToMany(User::class, 'taskuser');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
