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

    public function taskAssign()
    {
        return $this->belongsTo(TaskAssign::class);
    }
    public function getAccomplishemtValue($Taskaccomid,  $Taskmesurementid){
        $TaskMeasurementAcomplishment = TaskMeasurementAcomplishment::select('task_measurement_acomplishments.*')
                // ->join('task_assigns', 'task_assigns.id', '=', 'task_accomplishments.task_assign_id')
                 ->where('task_accomplishment_id',"=", $Taskaccomid)
                ->where('task_measurement_id',"=",$Taskmesurementid)
                ->get();
                return $TaskMeasurementAcomplishment;
    }
    public function getAccomplishemtValueUalue($Taskaccomid,  $Taskmesurementid){
        $Task_Measurement_Acomplishment = 0;
        $TaskMeasurementAcomplishment = TaskMeasurementAcomplishment::select('task_measurement_acomplishments.*')
                 ->where('task_accomplishment_id',"=", $Taskaccomid)
                ->where('task_measurement_id',"=",$Taskmesurementid)
                ->get();
                foreach ($TaskMeasurementAcomplishment as $key => $value) {
                   $Task_Measurement_Acomplishment = $value['accomplishment_value'];
                }
                return $Task_Measurement_Acomplishment;
    }
}
