<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KpiChildOneTranslation extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['kpiChildOne_id', 'name', 'description'];

    protected $searchableFields = ['*'];

    protected $table = 'kpi_child_one_translations';

    public function kpiChildOne()
    {
        return $this->belongsTo(KpiChildOne::class, 'kpiChildOne_id');
    }
}
