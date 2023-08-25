<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiType extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'updated_at',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'kpi_types';
    public function kpiTypeTranslations()
    {
        return $this->hasMany(KpiTypeTranslation::class, 'type_id');
    }

    public function kpi(){
        return $this->hasOne(KeyPeformanceIndicator::class, 'kpi_type_id');
    }
}
