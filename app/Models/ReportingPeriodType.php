<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportingPeriodType extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [];

    protected $searchableFields = ['*'];

    protected $table = 'reporting_period_types';

    public function reportingPeriods()
    {
        return $this->hasMany(ReportingPeriod::class);
    }

    public function keyPeformanceIndicators()
    {
        return $this->hasMany(KeyPeformanceIndicator::class);
    }

    public function reportingPeriodTypeTs()
    {
        return $this->hasMany(ReportingPeriodTypeT::class);
    }
}
