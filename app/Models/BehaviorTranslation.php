<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\Searchable;

class BehaviorTranslation extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'translation_id',
        'name',
        'slug',
        'description',
        'locale',
    ];
    protected $table = 'behavior_translations';

    protected $searchableFields = ['*'];

    public function behavior()
    {
        return $this->belongsTo(Behavior::class, 'translation_id');
    }
}
