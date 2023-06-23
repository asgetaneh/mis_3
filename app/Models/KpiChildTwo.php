<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KpiChildTwo extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['kpi_child_one_id'];

    protected $searchableFields = ['*'];

    protected $table = 'kpi_child_twos';

    public function kpiChildOne()
    {
        return $this->belongsTo(KpiChildOne::class);
    }

    public function kpiChildTwoTranslations()
    {
        return $this->hasMany(KpiChildTwoTranslation::class);
    }

    public function kpiChildThrees()
    {
        return $this->belongsToMany(KpiChildThree::class); // changed from hasMany to belongsToMany
    }

    public function planAccomplishments()
    {
        return $this->hasMany(PlanAccomplishment::class);
    }
}
