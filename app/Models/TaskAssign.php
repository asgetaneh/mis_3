<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssign extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'assigned_by_id',
        'assigned_to_id',
        'assigned_at',
        'start_date',
        'end_date',
        'expected_value',
        'status',
        'time_gap',
        'reject_reason',
        'challenge',
    ];
}
