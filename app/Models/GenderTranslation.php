<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GenderTranslation extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['name', 'description', 'gender_id'];

    protected $searchableFields = ['*'];

    protected $table = 'gender_translations';

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }
}
