<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KpiChildThree extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['kpi_child_two_id'];

    protected $searchableFields = ['*'];

    protected $table = 'kpi_child_threes';

    public function kpiChildThreeTranslations()
    {
        return $this->hasMany(
            KpiChildThreeTranslation::class,
            'kpiChildThree_id'
        );
    }

    public function kpiChildTwo()
    {
        return $this->belongsTo(KpiChildTwo::class);
    }

    public function planAccomplishments()
    {
        return $this->hasMany(PlanAccomplishment::class);
    }
}
