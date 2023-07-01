<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KpiChildTwo extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['key_peformance_indicator_id'];

    protected $searchableFields = ['*'];

    protected $table = 'kpi_child_twos';

    public function kpi()
    {
        return $this->belongsTo(KeyPeformanceIndicator::class);
    }

    public function kpiChildTwoTranslations()
    {
        return $this->hasMany(KpiChildTwoTranslation::class);
    }

    public function planAccomplishments()
    {
        return $this->hasMany(PlanAccomplishment::class);
    }
}
