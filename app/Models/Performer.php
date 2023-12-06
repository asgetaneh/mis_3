<?php

namespace App\Models;
use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Performer extends Model
{
   use HasFactory;
    use Searchable;

    protected $fillable = [
        'office_id',
        'user_id',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'performers';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
     public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }
    public function getTaskByperformer($performer) {
        $tasks = Task::select('tasks.*')
             ->join('task_assigns', 'tasks.id', '=', 'task_assigns.task_id')
              ->join('users', 'users.id', '=', 'task_assigns.assigned_to_id')
              ->join('performers', 'performers.user_id', '=', 'users.id')
            ->where('performers.id','=', $performer) 
            ->get();
             return $tasks;
    }
}
