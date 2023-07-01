<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KpiChildOne extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['key_peformance_indicator_id'];

    protected $searchableFields = ['*'];

    protected $table = 'kpi_child_ones';

    public function keyPeformanceIndicator()
    {
        return $this->belongsTo(KeyPeformanceIndicator::class);
    }

    public function kpiChildOneTranslations()
    {
        return $this->hasMany(KpiChildOneTranslation::class, 'kpiChildOne_id');
    }
    public function planAccomplishments()
    {
        return $this->hasMany(PlanAccomplishment::class);
    }
}
