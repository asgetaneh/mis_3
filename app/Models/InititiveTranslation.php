<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InititiveTranslation extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['inititive_id', 'name', 'description'];

    protected $searchableFields = ['*'];

    protected $table = 'inititive_translations';

    public function inititive()
    {
        return $this->belongsTo(Inititive::class);
    }
}
