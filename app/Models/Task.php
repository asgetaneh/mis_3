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
}
