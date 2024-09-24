<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
    ];

    protected $searchableFields = ['*'];

    public function keyPerformanceIndicators()
    {
        return $this->hasMany(KeyPeformanceIndicator::class);
    }

    public function measurementTranslations()
    {
        return $this->hasMany(MeasurementTranslation::class, 'translation_id');
    }
}
