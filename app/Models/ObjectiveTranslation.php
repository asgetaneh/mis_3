<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObjectiveTranslation extends Model
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

    protected $table = 'objective_translations';

    public function objective()
    {
        return $this->belongsTo(Objective::class, 'translation_id');
    }
}
