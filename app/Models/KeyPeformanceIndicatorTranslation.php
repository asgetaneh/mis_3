<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KeyPeformanceIndicatorTranslation extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'name',
        'description',
        'out_put',
        'out_come',
        'translation_id',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'key_peformance_indicator_translations';

    public function keyPeformanceIndicator()
    {
        return $this->belongsTo(
            KeyPeformanceIndicator::class,
            'translation_id'
        );
    }
}
