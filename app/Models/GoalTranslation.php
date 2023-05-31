<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoalTranslation extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'translation_id',
        'name',
        'out_put',
        'out_come',
        'description',
        'locale',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'goal_translations';

    public function goal()
    {
        return $this->belongsTo(Goal::class, 'translation_id');
    }
}
