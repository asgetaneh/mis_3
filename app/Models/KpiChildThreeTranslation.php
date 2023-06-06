<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KpiChildThreeTranslation extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['name', 'description', 'kpiChildThree_id'];

    protected $searchableFields = ['*'];

    protected $table = 'kpi_child_three_translations';

    public function kpiChildThree()
    {
        return $this->belongsTo(KpiChildThree::class, 'kpiChildThree_id');
    }
}
