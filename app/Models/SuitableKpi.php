<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuitableKpi extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'key_peformance_indicator_id',
        'office_id',
        'planing_year_id',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'suitable_kpis';

    public function keyPeformanceIndicator()
    {
        return $this->belongsTo(KeyPeformanceIndicator::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function planingYear()
    {
        return $this->belongsTo(PlaningYear::class);
    }

    public function planAccomplishments()
    {
        return $this->hasMany(PlanAccomplishment::class);
    }
}
