<?php

namespace App\Models;
use App\Models\Scopes\Searchable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskMeasurement extends Model
{
    use HasFactory;
    use Searchable;
    protected $searchableFields = ['*'];
     public function task()
    {
        return $this->belongsToMany(Task::class, 'task_task_measures');
    }
}
