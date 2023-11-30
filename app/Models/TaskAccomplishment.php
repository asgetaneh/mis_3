<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAccomplishment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_assign_id',
        'accomplishment_value',
        'reported_value',
        'reported_at',
        'task_done_description',
    ];
}
