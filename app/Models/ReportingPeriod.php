<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportingPeriod extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'planing_year_id',
        'start_date',
        'end_date',
        'reporting_period_type_id',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'reporting_periods';

    public function planingYear()
    {
        return $this->belongsTo(PlaningYear::class);
    }

    public function reportingPeriodType()
    {
        return $this->belongsTo(ReportingPeriodType::class);
    }

    public function planAccomplishments()
    {
        return $this->hasMany(PlanAccomplishment::class);
    }

    public function reportingPeriodTs()
    {
        return $this->hasMany(ReportingPeriodT::class);
    }
}
