<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskColumnOrder extends Model
{
    use HasFactory;

    protected $table = 'taskcolumn_order';

    protected $fillable = [
        'column_order'
    ];

}
