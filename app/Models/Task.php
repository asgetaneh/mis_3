<?php

namespace App\Models;
use App\Models\Scopes\Searchable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'kpi_id',
        'period_id',
        'created_by_id',
        'office_id',
        'name',
        'description',
        'weight',
    ];

    protected $searchableFields = ['*'];
    public function kpi()
    {
        return $this->belongsTo(KeyPeformanceIndicator::class, 'kpi_id');
    }
    public function office()
    {
        // return $this->hasMany(Office::class, 'office_id');
        return $this->belongsTo(Office::class);

    }
    public function period()
    {
        return $this->belongsTo(ReportingPeriod::class, 'period_id');
    }
    public function taskMeasurement()
    {
        return $this->belongsToMany(TaskMeasurement::class, 'task_task_measures');
    }
    public function assignedTasks()
    {
        return $this->hasMany(TaskAssign::class, 'task_id');
    } 
    public function taskAccomplishments()
    {
        return $this->hasManyThrough(TaskAccomplishment::class, TaskAssign::class);
    }
     public function getTaskAssignment($Task,  $Tperformer){
        $task_ass = TaskAssign::select('task_assigns.*')
               //  ->join('task_assigns', 'task_assigns.task_id', '=','tasks.id')
                 ->join('users', 'users.id', '=', 'task_assigns.assigned_to_id')
                 ->join('performers', 'performers.user_id', '=', 'users.id')
                 ->where('performers.id',"=", $Tperformer)
                ->where('task_assigns.task_id',"=",$Task)
                ->get();
                return $task_ass;
    }
    public function getTaskAccomplishmentValue($task_measure,  $task_accom_para){
        $task_accom = TaskMeasurementAcomplishment::select('task_measurement_acomplishments.*')
               //  ->join('task_assigns', 'task_assigns.task_id', '=','tasks.id')
                 ->join('task_measurements', 'task_measurements.id', '=', 'task_measurement_acomplishments.task_measurement_id')
                 ->join('task_accomplishments', 'task_accomplishments.id', '=', 'task_measurement_acomplishments.task_accomplishment_id')
                 ->where('task_measurements.id',"=", $task_measure)
                ->where('task_accomplishments.id',"=",$task_accom_para)
                ->get();
                if($task_accom->empty()){
                    foreach ($task_accom as $key => $value) { //dd($value);
                        return $value['accomplishment_value'];
                    }
                    
                }
                return "--";
                
    }
 }
