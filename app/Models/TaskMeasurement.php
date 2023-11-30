<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskMeasurement extends Model
{
    use HasFactory;
     public function task()
    {
        return $this->belongsToMany(Task::class, 'task_task_measures');
    }
}
