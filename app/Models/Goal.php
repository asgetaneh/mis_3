<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Goal extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['is_active', 'created_by_id', 'updated_by'];

    protected $searchableFields = ['*'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function goalTranslations()
    {
        return $this->hasMany(GoalTranslation::class, 'translation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function objectives()
    {
        return $this->hasMany(Objective::class);
    }
}
