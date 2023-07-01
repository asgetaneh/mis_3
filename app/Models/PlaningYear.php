<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlaningYear extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [];

    protected $searchableFields = ['*'];

    protected $table = 'planing_years';

    public function reportingPeriods()
    {
        return $this->hasMany(ReportingPeriod::class);
    }

    public function planingYearTranslations()
    {
        return $this->hasMany(PlaningYearTranslation::class);
    }
    public function planAccomplishments()
    {
        return $this->hasMany(PlanAccomplishment::class);
    }

    public function suitableKpis()
    {
        return $this->hasMany(SuitableKpi::class);
    }
}
