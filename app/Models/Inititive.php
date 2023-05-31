<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inititive extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['key_peformance_indicator_id'];

    protected $searchableFields = ['*'];

    public function inititiveTranslations()
    {
        return $this->hasMany(InititiveTranslation::class);
    }

    public function keyPeformanceIndicator()
    {
        return $this->belongsTo(KeyPeformanceIndicator::class);
    }

    public function genders()
    {
        return $this->belongsToMany(Gender::class, 'inititive_gender');
    }
}
