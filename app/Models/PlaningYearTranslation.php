<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlaningYearTranslation extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['planing_year_id', 'name', 'description'];

    protected $searchableFields = ['*'];

    protected $table = 'planing_year_translations';

    public function planingYear()
    {
        return $this->belongsTo(PlaningYear::class);
    }
}
