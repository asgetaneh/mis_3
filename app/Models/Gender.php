<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gender extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [];

    protected $searchableFields = ['*'];

    public function genderTranslations()
    {
        return $this->hasMany(GenderTranslation::class);
    }

    public function inititives()
    {
        return $this->belongsToMany(Inititive::class, 'inititive_gender');
    }
}
