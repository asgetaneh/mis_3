<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Strategy extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['objective_id', 'created_by_id', 'updated_by_id'];

    protected $searchableFields = ['*'];

    public function objective()
    {
        return $this->belongsTo(Objective::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function strategyTranslations()
    {
        return $this->hasMany(StrategyTranslation::class, 'translation_id');
    }

    public function keyPeformanceIndicators()
    {
        return $this->hasMany(KeyPeformanceIndicator::class);
    }
}
