<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KeyPeformanceIndicator extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'weight',
        'objective_id',
        'strategy_id',
        'created_by_id',
        'reporting_period_type_id',
        'behavior_id',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'key_peformance_indicators';

    public function objective()
    {
        return $this->belongsTo(Objective::class);
    }

    public function strategy()
    {
        return $this->belongsTo(Strategy::class);
    }
    public function behavior()
    {
        return $this->belongsTo(Behavior::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function reportingPeriodType()
    {
        return $this->belongsTo(ReportingPeriodType::class);
    }

    public function inititives()
    {
        return $this->hasMany(Inititive::class);
    }

    public function suitableKpis()
    {
        return $this->hasMany(SuitableKpi::class);
    }

    public function keyPeformanceIndicatorTs()
    {
        return $this->hasMany(KeyPeformanceIndicatorT::class, 'translation_id');
    }

    public function offices()
    {
        return $this->belongsToMany(Office::class, 'kpi_office', 'kpi_id');
    }
    public function planacc()
    {
        return $this->hasMany(PlanAccomplishment::class,'kpi_id');
    }
    public function kpiChildOnes()
    {
        return $this->belongsToMany(KpiChildOne::class);
    }

    public function kpiChildTwos()
    {
        return $this->belongsToMany(KpiChildTwo::class);
    }
    public function kpiChildThrees()
    {
        return $this->belongsToMany(KpiChildThree::class); // changed from hasMany to belongsToMany
    }



}
