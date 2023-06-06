<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KpiChildTwoTranslation extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['name', 'description', 'kpi_child_two_id'];

    protected $searchableFields = ['*'];

    protected $table = 'kpi_child_two_translations';

    public function kpiChildTwo()
    {
        return $this->belongsTo(KpiChildTwo::class);
    }
}
