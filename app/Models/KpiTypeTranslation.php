<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiTypeTranslation extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['type_id', 'name', 'description'];

    protected $searchableFields = ['*'];

    protected $table = 'kpi_type_translations';

    public function kpiType()
    {
        return $this->belongsTo(KpiType::class, 'type_id');
    }

}
