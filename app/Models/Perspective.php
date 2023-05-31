<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perspective extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['created_by_id', 'updated_by_id'];

    protected $searchableFields = ['*'];

    public function perspectiveTranslations()
    {
        return $this->hasMany(PerspectiveTranslation::class, 'translation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function objectives()
    {
        return $this->hasMany(Objective::class);
    }
}
