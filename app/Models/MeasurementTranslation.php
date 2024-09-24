<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\Searchable;

class MeasurementTranslation extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'status',
        'translation_id',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'measurement_translations';

    public function measurement()
    {
        return $this->belongsTo(Measurement::class, 'translation_id');
    }
}
