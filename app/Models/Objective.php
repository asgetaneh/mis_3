<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Objective extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'goal_id',
        'perspective_id',
        'created_by_id',
        'updated_by_id',
        'weight',
    ];

    protected $searchableFields = ['*'];

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function perspective()
    {
        return $this->belongsTo(Perspective::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function objectiveTranslations()
    {
        return $this->hasMany(ObjectiveTranslation::class, 'translation_id');
    }

    public function strategies()
    {
        return $this->hasMany(Strategy::class);
    }

    public function keyPeformanceIndicators()
    {
        return $this->hasMany(KeyPeformanceIndicator::class);
    }
}
